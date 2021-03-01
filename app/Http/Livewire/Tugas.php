<?php

namespace App\Http\Livewire;

use App\Models\Matkul;
use App\Models\Tugas as ModelsTugas;
use App\Traits\LivewireAlert;
use DateTime;
use Livewire\Component;
use Livewire\WithPagination;

class Tugas extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $form, $id_tugas, $deskripsi, $batas_waktu, $tugas, $selesai, $pertemuan_ke, $matkul = '', $matkuls;

    public $paginate_per_page = 5;

    public $search = '';
    public $page = 1;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'matkul'        => 'required',
        'deskripsi'     => 'required',
        'batas_waktu'   => 'required',
        'pertemuan_ke'  => 'required',
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

        $this->tugas_yg_ga_selesai = Matkul::with([
            'semester' => function ($q) {
                $q->where('user_id', auth()->user()->id)->where('aktif_smt', 1);
            },
            'tugas' => function ($q) {
                $q->where('user_id', auth()->user()->id)->where('selesai', null);
            }
        ])->get();

        if (auth()->user()->hasRole('admin')) {

            $all_tugas = ModelsTugas::where('deskripsi', 'like', '%' . $this->search . '%')
                ->orWhere('batas_waktu', 'like', '%' . $this->search . '%')
                ->orWhere('selesai', 'like', '%' . $this->search . '%')
                ->orWhere('pertemuan_ke', 'like', '%' . $this->search . '%')
                ->orWhere('created_at', 'like', '%' . $this->search . '%')
                ->orWhere('updated_at', 'like', '%' . $this->search . '%')
                ->orWhereHas('matkul', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('username', 'like', '%' . $this->search . '%')
                        ->groupBy('name');
                })
                ->orderBy('selesai', 'asc')
                ->paginate($this->paginate_per_page);

            $tugas_yg_ga_selesai = Matkul::with([
                'semester',
                'tugas' => function ($q) {
                    $q->where('selesai', null);
                }
            ])->get();
        } else {
            $all_tugas = ModelsTugas::where('user_id', auth()->user()->id)
                ->where(function ($q) {
                    $q->where('deskripsi', 'like', '%' . $this->search . '%')
                        ->orWhere('batas_waktu', 'like', '%' . $this->search . '%')
                        ->orWhere('selesai', 'like', '%' . $this->search . '%')
                        ->orWhere('pertemuan_ke', 'like', '%' . $this->search . '%')
                        ->orWhere('created_at', 'like', '%' . $this->search . '%')
                        ->orWhere('updated_at', 'like', '%' . $this->search . '%')
                        ->orWhereHas('matkul', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        });
                })
                ->orderBy('selesai', 'asc')
                ->paginate($this->paginate_per_page);

            $tugas_yg_ga_selesai = Matkul::with([
                'semester' => function ($q) {
                    $q->where('user_id', auth()->user()->id)->where('aktif_smt', 1);
                },
                'tugas' => function ($q) {
                    $q->where('user_id', auth()->user()->id)->where('selesai', null);
                }
            ])->get();
        }

        // get all matkul
        $this->matkuls = Matkul::where('user_id', auth()->user()->id)->get();

        return view('livewire.tugas', compact('all_tugas', 'tugas_yg_ga_selesai'));
    }

    public function showForm($type)
    {
        $this->form = $type;

        if ($this->deskripsi) {
            $this->emptyItems();
        }
    }

    public function hideForm()
    {
        $this->form = '';
        $this->emptyItems();
        $this->noValidate();
    }


    public function emptyItems()
    {
        $this->matkul = '';
        $this->deskripsi = '';
        $this->batas_waktu = '';
        $this->selesai = '';
        $this->pertemuan_ke = '';
    }

    public function store()
    {
        $this->validate();

        $matkul = new ModelsTugas;
        $matkul->matkul_id      = $this->matkul;
        $matkul->user_id        = auth()->user()->id;
        $matkul->deskripsi      = $this->deskripsi;
        $matkul->batas_waktu    = $this->batas_waktu;
        $matkul->pertemuan_ke   = $this->pertemuan_ke;
        $matkul->save();

        $this->hideForm();

        $this->showAlert('success', 'Tugas berhasil ditambahkan.');
    }

    public function show($id)
    {
        $this->noValidate();

        $this->id_tugas = $id;
        $tugas = ModelsTugas::findOrfail($id);

        if (auth()->user()->hasRole('admin') || $tugas->user_id == auth()->user()->id) {
            // get all matkul
            $this->matkuls = Matkul::get();
            $this->matkul       = $tugas->matkul_id;
            $this->deskripsi    = $tugas->deskripsi;
            $this->batas_waktu  = date('Y-m-d\TH:i', strtotime($tugas->batas_waktu));
            $this->selesai      = !$tugas->selesai ? $tugas->selesai : date('Y-m-d\TH:i', strtotime($tugas->selesai));
            $this->pertemuan_ke = $tugas->pertemuan_ke;
            $this->form         = 'edit';
        } else {
            $this->showAlert('error', 'Tugas tidak dapat diubah.');
        }
    }

    public function update($id)
    {
        $this->validate();

        $tugas = ModelsTugas::find($id);

        $batasWaktu = new DateTime($this->batas_waktu);
        $tglSelesai = new DateTime($this->selesai);

        $batasWaktuCount = date('YmdHi', strtotime($this->batas_waktu));

        $tglSelesaiCount =  date('YmdHi', strtotime($this->selesai));

        // cek jika tugas milik user yang sedang login
        if (auth()->user()->hasRole('admin') || $tugas->user_id == auth()->user()->id) {

            // jika batas waktu telah habis
            if ($tglSelesaiCount > $batasWaktuCount) {

                $this->selesai = null;

                $this->validate(
                    [
                        'selesai' => 'required'
                    ],
                    ['required' => 'Tanggal selesai tidak boleh lebih besar dari batas waktu!']
                );
            } else {
                $tugas->matkul_id      = $this->matkul;
                $tugas->deskripsi      = $this->deskripsi;
                $tugas->batas_waktu    = $this->batas_waktu;
                $tugas->selesai        = $this->selesai;
                $tugas->pertemuan_ke   = $this->pertemuan_ke;
                $tugas->save();

                $this->hideForm();

                $this->showAlert('success', 'Tugas berhasil diubah.');
            }
        } else {
            $this->showAlert('error', 'Tugas tidak dapat diubah.');
        }
    }


    public function noValidate()
    {
        $this->validate([
            'matkul'        => '',
            'deskripsi'     => '',
            'batas_waktu'   => '',
            'selesai'       => ''
        ]);
    }


    public function confirmed()
    {
        $tugas = ModelsTugas::findOrfail($this->id_tugas);

        if (auth()->user()->hasRole('admin') || $tugas->user_id == auth()->user()->id) {
            $tugas->destroy($this->id_tugas);
            $this->showAlert('success', 'Tugas berhasil dihapus.');
        } else {
            $this->showAlert('error', 'Tugas tidak dapat dihapus.');
        }

        $this->id_tugas = '';
        $this->hideForm();
    }

    public function cancelled()
    {
        $this->id_tugas = '';
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
        $this->id_tugas = $id;
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
