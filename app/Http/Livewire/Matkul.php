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

    public $form, $id_matkul, $name, $dosen, $hari, $jam_mulai, $jam_selesai, $sks, $semesters, $semester_id = '', $milik_user;
    public $paginate_per_page = 5;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $page = 1;

    protected $rules = [
        'name' => 'required',
        'dosen' => 'required',
        'hari' => 'required',
        'jam_mulai' => 'required',
        'jam_selesai' => 'required',
        'sks' => 'required',
        'semester_id' => 'required',
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
                ->orWhere('dosen', 'like', '%' . $this->search . '%')
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

            $jadwal_hari_ini = ModelsMatkul::with([
                'semester' => function ($q) {
                    $q->where('aktif_smt', 1);
                }
            ])
                ->where('hari', $today)
                ->get();
        } else {
            // end user
            $matkuls = ModelsMatkul::where('user_id', auth()->user()->id)
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('hari', 'like', '%' . $this->search . '%')
                        ->orWhere('dosen', 'like', '%' . $this->search . '%')
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

            $jadwal_hari_ini = ModelsMatkul::with([
                'semester' => function ($q) {
                    $q->where('user_id', auth()->user()->id)->where('aktif_smt', 1);
                }
            ])
                ->where('user_id', auth()->user()->id)
                ->where('hari', $today)
                ->get();
        }

        return view('livewire.matkul', compact('matkuls', 'jadwal_hari_ini'));
    }

    public function showForm($type)
    {
        $this->form = $type;

        if ($type == 'add') {
            // get all semesters
            $this->semesters = Semester::where('user_id', auth()->user()->id)->get();
            $this->emptyItems();
        }
    }

    public function hideForm()
    {
        $this->form = '';
        $this->emptyItems();
        $this->noValidate();
    }

    public function noValidate()
    {
        $this->validate([
            'name' => '',
            'dosen' => '',
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
        $this->dosen = '';
        $this->jam_selesai = '';
        $this->jam_mulai = '';
        $this->sks = '';
        $this->semester_id = '';
    }

    public function store()
    {
        $this->validate();

        if ($this->jam_mulai > $this->jam_selesai) {
            $this->jam_selesai = null;
            $this->validate(
                [
                    'jam_selesai' => 'required'
                ],
                ['required' => 'Jam Selesai selesai tidak boleh lebih kecil dari Jam Mulai!']
            );
        } else {
            $matkul = new ModelsMatkul;
            $matkul->name = $this->name;
            $matkul->dosen = $this->dosen;
            $matkul->hari = $this->hari;
            $matkul->jam_mulai = $this->jam_mulai;
            $matkul->jam_selesai = $this->jam_selesai;

            $matkul->user_id = auth()->user()->id;
            $matkul->sks = $this->sks;
            $matkul->semester_id = $this->semester_id;
            $matkul->save();

            $this->hideForm();

            $this->showAlert('success', 'Mata Kuliah berhasil ditambahkan.');
        }
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

    public function show($id)
    {
        $this->noValidate();

        $this->id_matkul = $id;
        $matkul = ModelsMatkul::findOrfail($id);

        if (auth()->user()->hasRole('admin') || $matkul->user_id == auth()->user()->id) {

            if ($matkul->user_id != auth()->user()->id) {
                $this->showAlert('info', 'Kamu sedang mengubah mata kuliah user lain!.');
                $this->milik_user = User::find($matkul->user_id);
                $this->semesters =  Semester::where('user_id', $matkul->user_id)->get();
            } else {
                $this->semesters = Semester::where('user_id', auth()->user()->id)->get();
                $this->milik_user = [];
            }

            $this->name = $matkul->name;
            $this->dosen = $matkul->dosen;
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

        if (auth()->user()->hasRole('admin') || $matkul->user_id == auth()->user()->id) {

            if ($this->jam_mulai > $this->jam_selesai) {
                $this->jam_selesai = null;
                $this->validate(
                    [
                        'jam_selesai' => 'required'
                    ],
                    ['required' => 'Jam Selesai selesai tidak boleh lebih kecil dari Jam Mulai!']
                );
            } else {
                $matkul->name = $this->name;
                $matkul->dosen = $this->dosen;
                $matkul->hari = $this->hari;
                $matkul->jam_mulai = $this->jam_mulai;
                $matkul->jam_selesai = $this->jam_selesai;
                $matkul->sks = $this->sks;
                $matkul->semester_id = $this->semester_id;
                $matkul->save();

                $this->showAlert('success', 'Mata Kuliah berhasil diubah.');
            }
        } else {
            $this->showAlert('error', 'Mata Kuliah tidak dapat diubah.');
        }

        $this->hideForm();
    }

    public function confirmed()
    {
        $matkul = ModelsMatkul::findOrFail($this->id_matkul);

        if (auth()->user()->hasRole('admin') || $matkul->user_id == auth()->user()->id) {
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
