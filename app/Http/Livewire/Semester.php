<?php

namespace App\Http\Livewire;

use App\Models\Semester as ModelsSemester;
use App\Models\User;
use App\Traits\LivewireAlert;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class Semester extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $form, $id_semester, $semester_ke, $aktif_smt, $select_semesters = [], $milik_user;

    public $search = '';
    public $page = 1;
    public $paginate_per_page = 5;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'semester_ke' => 'required|numeric|in:1,2,3,4,5,6,7,8',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $listeners = [
        'confirmed',
        'cancelled',
    ];

    public function mount()
    {
        $this->fill(request()->only('search', 'page'));
    }

    public function render()
    {
        $this->select_semesters = ModelsSemester::get();

        $this->aktif_smt = ModelsSemester::select('id', 'semester_ke')
            ->where('user_id', auth()->id())
            ->where(function ($q) {
                $q->where('aktif_smt', 1);
            })->first();

        if (auth()->user()->hasRole('admin')) {
            $semesters = ModelsSemester::where('semester_ke', 'like', '%' . $this->search . '%')
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->orderBy('created_at', 'asc')->paginate($this->paginate_per_page);
        } else {
            $semesters = ModelsSemester::where('user_id', auth()->id())
                ->where(function ($q) {
                    $q->where('semester_ke', 'like', '%' . $this->search . '%');
                })
                ->orderBy('created_at', 'asc')->paginate($this->paginate_per_page);
        }
        return view('livewire.semester', compact('semesters'));
    }

    // show modal
    public function showForm($type)
    {
        $this->milik_user = '';
        $this->form = $type;
        $this->noValidate();
        $this->emptyItems();
    }

    // hide modal
    public function hideForm()
    {
        $this->form = '';
        $this->milik_user = '';
        $this->noValidate();
        $this->emptyItems();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function noValidate()
    {
        $this->validate([
            'semester_ke' => ''
        ]);
    }

    public function store()
    {
        $this->validate();

        // jika semester yang sama sudah ada
        $check = $this->checkDuplicateSemesterKe();
        if ($check) {
            $this->semester_ke = '';
            $this->validate(
                ['semester_ke' => 'required'],
                ['required' => "Semester $check->semester_ke sudah ada!"]
            );
        } else {
            ModelsSemester::create([
                'user_id' => auth()->id(),
                'semester_ke' => $this->semester_ke,
            ]);

            $this->hideForm();
            $this->showAlert('success', 'Semester berhasil ditambahkan.');
        }
    }

    public function show($id)
    {
        $this->noValidate();

        $this->milik_user = '';
        $this->id_semester = $id;

        $semester = ModelsSemester::findOrFail($id);

        if (auth()->user()->hasRole('admin') || $semester->user_id == auth()->id()) {

            if ($semester->user_id != auth()->id()) {
                $this->milik_user = User::find($semester->user_id);
            } else {
                $this->milik_user = '';
            }

            $this->semester_ke = $semester->semester_ke;
            $this->form = 'edit';
        } else {
            $this->hideForm();
            $this->showAlert('error', 'Semester tidak dapat diubah.');
        }
    }

    public function update($id)
    {
        $this->validate();

        // jika semester yang sama sudah ada tetapi pada id yang beda
        $check = $this->checkDuplicateSemesterKe();
        if ($check && $check->id != $this->id_semester) {
            // error validation
            $this->semester_ke = '';

            $this->validate(
                ['semester_ke' => 'required'],
                // jika $this->milik_user maka admin yang ingin ubah data user lain
                ['required' => $this->milik_user ?  $this->milik_user->name . ' sudah memiliki semester ' . $check->semester_ke : "Semester $check->semester_ke sudah ada!"]
            );
        }

        $semester = ModelsSemester::findOrFail($id);

        // jika user ingin edit data yang bukan miliknya
        if (auth()->user()->hasRole('admin') || $semester->user_id == auth()->id()) {
            $semester->semester_ke = $this->semester_ke;
            $semester->save();

            $this->hideForm();

            $this->showAlert('success', 'Semester berhasil diubah.');
        } else {
            $this->hideForm();

            $this->showAlert('error', 'Semester tidak dapat diubah.');
        }
    }

    public function checkDuplicateSemesterKe()
    {
        /**
         * jika admin ingin ubah semeser user lain
         * cek apakah user tersebut sudah punya semester yang sama
         */
        if ($this->milik_user) {
            $check = ModelsSemester::where([
                'user_id' => $this->milik_user->id,
                'semester_ke' => $this->semester_ke
            ])->first();
        } else {
            // jika yang login mahasiswa atau admin ingin tambah semester baru
            $check = ModelsSemester::where([
                'user_id' => auth()->id(),
                'semester_ke' => $this->semester_ke
            ])->first();
        }

        if ($check) {
            return $check;
        } else {
            return false;
        }
    }

    public function emptyItems()
    {
        $this->semester_ke = '';
    }

    public function setAktifSmt($id)
    {
        if ($this->aktif_smt) {
            if ($id != $this->aktif_smt['id']) {
                $this->updateAktifSmt($id);
                // set smt aktif sebelumnya ke null, agar cuma ada 1 smt aktif
                $semester = ModelsSemester::find($this->aktif_smt['id']);
                $semester->aktif_smt = null;
                $semester->save();
            }
        } else {
            $this->updateAktifSmt($id);
        }
    }

    public function updateAktifSmt($id)
    {
        $semester_aktif = ModelsSemester::findOrfail($id);

        if ($semester_aktif->user_id == auth()->id()) {
            $semester_aktif->aktif_smt = 1;
            $semester_aktif->save();

            $this->showAlert('success', 'Semester sekarang berhasil diubah.');
        } else {
            $this->showAlert('error', 'Semester sekarang tidak dapat diubah karena bukan semester milik kamu.');
        }
    }

    // jika user klik ya pada sweetalert
    public function confirmed()
    {
        $semester =  ModelsSemester::findOrFail($this->id_semester);
        if (auth()->user()->hasRole('admin') || $semester->user_id == auth()->id()) {

            // jika tidak terdapat relasi pada semester
            try {
                $semester->destroy($this->id_semester);
                $this->showAlert('success', 'Semester berhasil dihapus.');
            } catch (Exception $ex) {
                $this->showAlert('error', 'Tidak dapat dihapus karena terdapat mata kuliah pada semester : ' . $semester->semester_ke);
            }
        } else {
            $this->showAlert('error', 'Semester tidak dapat dihapus.');
        }

        $this->id_semester = '';
        $this->hideForm();
    }

    public function cancelled()
    {
        $this->id_semester = '';
        $this->alert('error', 'Dibatalkan', [
            'position'          =>  'top',
            'timer'             =>  1500,
            'toast'             =>  true,
            'showCancelButton'  =>  false,
            'showConfirmButton' =>  false
        ]);
    }

    public function triggerConfirm($id)
    {
        $this->id_semester = $id;
        $this->confirm('Yakin ingin menghapus data ini?', [
            'toast' => false,
            'position' => 'center',
            'confirmButtonText' =>  'ya',
            'cancelButtonText' =>  'Batal',
            'onConfirmed' => 'confirmed',
            'onCancelled' => 'cancelled'
        ]);
    }
}
