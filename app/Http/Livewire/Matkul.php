<?php

namespace App\Http\Livewire;

use App\Models\Matkul as ModelsMatkul;
use App\Models\Semester;
use App\Models\User;
use App\Traits\LivewireAlert;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class Matkul extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $form, $id_matkul, $name, $hari, $jam_mulai, $jam_selesai, $sks, $semesters, $semester_id = '', $milik_user;
    public $paginate_per_page = 5;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $page = 1;

    protected $rules = [
        'name'        => 'required|string|min:3',
        'semester_id' => 'required|numeric',
        'hari'        => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
        'jam_mulai'   => 'required',
        'jam_selesai' => 'required|after:jam_mulai',
        'sks'         => 'required|in:1,2,3,4,5,6',
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
        $today = $this->checkDay(date('l'));

        if (auth()->user()->hasRole('admin')) {
            // admin
            $matkuls = ModelsMatkul::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('hari', 'like', '%' . $this->search . '%')
                ->orWhere('jam_mulai', 'like', '%' . $this->search . '%')
                ->orWhere('jam_selesai', 'like', '%' . $this->search . '%')
                ->orWhere('sks', 'like', '%' . $this->search . '%')
                ->orWhere('created_at', 'like', '%' . $this->search . '%')
                ->orWhere('updated_at', 'like', '%' . $this->search . '%')
                ->orWhereHas('semester', function ($q) {
                    $q->where('semester_ke', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->orderBy('updated_at', 'desc')
                ->paginate($this->paginate_per_page);
        } else {
            // mahasiswa
            $matkuls = ModelsMatkul::where('user_id', auth()->id())
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('hari', 'like', '%' . $this->search . '%')
                        ->orWhere('jam_mulai', 'like', '%' . $this->search . '%')
                        ->orWhere('jam_selesai', 'like', '%' . $this->search . '%')
                        ->orWhere('sks', 'like', '%' . $this->search . '%')
                        ->orWhere('created_at', 'like', '%' . $this->search . '%')
                        ->orWhere('updated_at', 'like', '%' . $this->search . '%')
                        ->orWhereHas('semester', function ($q) {
                            $q->where('semester_ke', 'like', '%' . $this->search . '%');
                        });
                })
                ->orderBy('updated_at', 'desc')
                ->paginate($this->paginate_per_page);
        }

        $jadwal_hari_ini = ModelsMatkul::where('user_id', auth()->id())
            ->where('hari', $today)
            ->whereHas('semester', function ($q) {
                $q->where('aktif_smt', 1)->where('user_id', auth()->id());
            })
            ->get();

        return view('livewire.matkul', compact('matkuls', 'jadwal_hari_ini'));
    }

    public function showForm($type)
    {
        $this->milik_user = '';
        $this->form = $type;
        $this->noValidate();
        $this->emptyItems();

        /**
         * jika ingin tambah data baru, maka hanya tampilkan semester yang dimilik oleh user yang sedang login
         */
        if ($type == 'add') {
            $this->semesters = Semester::where('user_id', auth()->id())->get();
        }
    }

    public function hideForm()
    {
        $this->milik_user = '';
        $this->form = '';
        $this->dispatchBrowserEvent('close-modal');
        $this->emptyItems();
        $this->noValidate();
    }

    public function noValidate()
    {
        $this->validate([
            'name' => '',
            'hari' => '',
            'jam_mulai' => '',
            'jam_selesai' => '',
            'sks' => '',
            'semester_id' => ''
        ]);
    }

    public function emptyItems()
    {
        $this->name = '';
        $this->hari = '';
        $this->jam_selesai = '';
        $this->jam_mulai = '';
        $this->sks = '';
        $this->semester_id = '';
    }

    public function store()
    {
        $this->validate();

        // jika terdapat mata kuliah yang sama pada satu user
        $check = $this->checkDuplicateNamaMatkul();
        if ($check) {
            $this->name = '';
            $this->validate(
                ['name' => 'required'],
                ['required' => "Mata kuliah $check->name sudah ada!"]
            );
        }

        $matkul = new ModelsMatkul;
        $matkul->name = $this->name;
        $matkul->hari = $this->hari;
        $matkul->jam_mulai = $this->jam_mulai;
        $matkul->jam_selesai = $this->jam_selesai;
        $matkul->user_id = auth()->id();
        $matkul->sks = $this->sks;
        $matkul->semester_id = $this->semester_id;
        $matkul->save();

        $this->hideForm();

        $this->showAlert('success', 'Mata Kuliah berhasil ditambahkan.');
    }

    public function show($id)
    {
        $this->noValidate();

        $this->id_matkul = $id;
        $matkul = ModelsMatkul::findOrfail($id);

        if (auth()->user()->hasRole('admin') || $matkul->user_id == auth()->id()) {

            // jika yang login admin
            if ($matkul->user_id != auth()->id()) {
                $this->milik_user = User::find($matkul->user_id);
                $this->semesters =  Semester::where('user_id', $matkul->user_id)->get();
            } else {
                $this->semesters = Semester::where('user_id', auth()->id())->get();
                $this->milik_user = [];
            }

            $this->name = $matkul->name;
            $this->hari = $matkul->hari;
            $this->jam_mulai = $matkul->jam_mulai;
            $this->jam_selesai = $matkul->jam_selesai;
            $this->sks = $matkul->sks;
            $this->semester_id = $matkul->semester_id;
            $this->form = 'edit';
        } else {
            $this->showAlert('error', 'Mata Kuliah tidak dapat diubah.');
        }
    }

    public function update($id)
    {
        $this->validate();

        $matkul = ModelsMatkul::findOrFail($id);

        // jika terdapat mata kuliah yang sama pada satu user
        $check = $this->checkDuplicateNamaMatkul();
        if ($check && $check->id != $id) {
            $this->name = '';

            $this->validate(
                ['name' => 'required'],
                // jika $this->milik_user maka admin yang ingin ubah data user lain
                ['required' => $this->milik_user ?  $this->milik_user->name . ' sudah memiliki mata kuliah ' . $check->name : "Mata kuliah $check->name sudah ada!"]
            );
        }

        if (auth()->user()->hasRole('admin') || $matkul->user_id == auth()->id()) {

            $matkul->name = $this->name;
            $matkul->hari = $this->hari;
            $matkul->jam_mulai = $this->jam_mulai;
            $matkul->jam_selesai = $this->jam_selesai;
            $matkul->sks = $this->sks;
            $matkul->semester_id = $this->semester_id;
            $matkul->save();

            $this->showAlert('success', 'Mata Kuliah berhasil diubah.');
        } else {
            $this->showAlert('error', 'Mata Kuliah tidak dapat diubah.');
        }

        $this->hideForm();
    }

    public function checkDay($day)
    {
        if ($day == 'Monday') {
            $day = 'senin';
        } elseif ($day == 'Tuesday') {
            $day = 'selasa';
        } elseif ($day == 'Wednesday') {
            $day = 'rabu';
        } elseif ($day == 'Thursday') {
            $day = 'kamis';
        } elseif ($day == 'Friday') {
            $day = 'jumat';
        } elseif ($day == 'Saturday') {
            $day = 'sabtu';
        }

        return $day;
    }

    public function checkDuplicateNamaMatkul()
    {
        if ($this->milik_user) {
            $check = ModelsMatkul::where([
                'user_id' => $this->milik_user->id,
                'name' => $this->name
            ])->first();
        } else {
            $check = ModelsMatkul::where([
                'user_id' => auth()->id(),
                'name' => $this->name
            ])->first();
        }

        if ($check) {
            return $check;
        } else {
            return false;
        }
    }

    public function confirmed()
    {
        $matkul = ModelsMatkul::findOrFail($this->id_matkul);

        if (auth()->user()->hasRole('admin') || $matkul->user_id == auth()->id()) {
            try {
                $matkul->destroy($this->id_matkul);
                $this->showAlert('success', 'Mata Kuliah berhasil dihapus.');
            } catch (Exception $ex) {
                $this->showAlert('error', 'Tidak dapat dihapus karena terdapat tugas pada mata kuliah : ' . $matkul->name);
            }
        } else {
            $this->showAlert('error', 'Mata Kuliah tidak dapat dihapus.');
        }

        $this->id_matkul = '';
        $this->hideForm();
    }

    public function cancelled()
    {
        $this->id_matkul = '';
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
        $this->id_matkul = $id;
        $this->confirm('Yakin ingin menghapus data ini?', [
            'toast' => false,
            'position' => 'center',
            'confirmButtonText' =>  'Ya',
            'cancelButtonText' =>  'Batal',
            'onConfirmed' => 'confirmed',
            'onCancelled' => 'cancelled'
        ]);
    }
}
