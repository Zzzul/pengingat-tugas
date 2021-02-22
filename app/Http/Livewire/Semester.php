<?php

namespace App\Http\Livewire;

use App\Models\Semester as ModelsSemester;
use App\Traits\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Semester extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $form, $id_semester, $semester_ke, $aktif_smt, $select_semesters = [];

    public $search = '';
    public $page = 1;
    public $paginate_per_page = 5;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'semester_ke' => 'required',
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

        $this->aktif_smt = ModelsSemester::select('id', 'semester_ke')->where('aktif_smt', 1)->first();

        $semesters = ModelsSemester::where('semester_ke', 'like', '%' . $this->search . '%')->orderBy('created_at', 'asc')->paginate($this->paginate_per_page);
        return view('livewire.semester', compact('semesters'));
    }

    public function showForm($type)
    {
        $this->form = $type;

        if ($this->semester_ke) {
            $this->emptyItems();
        }
    }

    public function hideForm()
    {
        $this->form = '';
        $this->semester_ke = '';
        $this->noValidate();
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

        ModelsSemester::create([
            'user_id' => auth()->user()->id,
            'semester_ke' => $this->semester_ke,
        ]);

        $this->showAlert('success', 'Semester berhasil ditambahkan.');

        $this->semester_ke = '';
        $this->form = '';
    }

    public function show($id)
    {
        $this->noValidate();

        $this->id_semester = $id;

        $semester = ModelsSemester::findOrFail($id);

        if ($semester->user_id == auth()->user()->id) {
            $this->semester_ke = $semester->semester_ke;
            $this->form = 'edit';
        } else {
            $this->showAlert('error', 'Semester tidak dapat diubah.');
        }
    }

    public function update($id)
    {
        $this->validate();

        $semester = ModelsSemester::findOrFail($id);

        // jika user ingin edit data yang bukan miliknya
        if ($semester->user_id == auth()->user()->id) {
            $semester->user_id = auth()->user()->id;
            $semester->semester_ke = $this->semester_ke;
            $semester->save();

            $this->showAlert('success', 'Semester berhasil diubah.');
        } else {
            $this->showAlert('error', 'Semester tidak dapat diubah.');
        }

        $this->hideForm();
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

        if ($semester_aktif->user_id == auth()->user()->id) {
            $semester_aktif->aktif_smt = 1;
            $semester_aktif->save();

            $this->showAlert('success', 'Semester sekarang berhasil diubah.');
        } else {
            $this->showAlert('error', 'Semester sekarang tidak dapat diubah.');
        }
    }

    public function confirmed()
    {
        $semester =  ModelsSemester::findOrFail($this->id_semester);
        if ($semester->user_id == auth()->user()->id) {
            $semester->destroy($this->id_semester);

            $this->showAlert('success', 'Semester berhasil dihapus.');
            $this->id_semester = '';
        } else {
            $this->showAlert('error', 'Semester tidak dapat dihapus.');
        }

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
