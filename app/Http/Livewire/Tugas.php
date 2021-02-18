<?php

namespace App\Http\Livewire;

use App\Models\Matkul;
use App\Models\Tugas as ModelsTugas;
use DateTime;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Tugas extends Component
{

    use WithPagination;

    public $form, $id_tugas, $deskripsi, $batas_waktu, $tugas, $selesai, $pertemuan_ke, $matkul = '', $matkuls, $tugas_yg_ga_selesai;

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
                $q->where('aktif_smt', 1);
            },
            'tugas' => function ($q) {
                $q->where([
                    // ['batas_waktu', '<=', date('Y-m-d H:i')],
                    ['selesai', '=', null]
                ]);
            }
        ])->get();

        // get all task
        $all_tugas = ModelsTugas::where('deskripsi', 'like', '%' . strtolower($this->search) . '%')
            ->orWhere('batas_waktu', 'like', '%' . strtolower($this->search) . '%')
            ->orWhere('selesai', 'like', '%' . strtolower($this->search) . '%')
            ->orWhere('pertemuan_ke', 'like', '%' . strtolower($this->search) . '%')
            ->orWhere('created_at', 'like', '%' . strtolower($this->search) . '%')
            ->orWhere('updated_at', 'like', '%' . strtolower($this->search) . '%')
            ->orWhereHas('matkul', function ($q) {
                $q->where('name', 'like', '%' . strtolower($this->search) . '%');
            })
            ->orderBy('selesai', 'asc')
            ->paginate($this->paginate_per_page);


        // get all matkul
        $this->matkuls = Matkul::get();

        return view('livewire.tugas', compact('all_tugas'));
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
        $matkul->deskripsi      = strtolower($this->deskripsi);
        $matkul->batas_waktu    = $this->batas_waktu;
        $matkul->pertemuan_ke   = $this->pertemuan_ke;
        $matkul->save();

        $this->hideForm();

        $this->showAlert('Mata Kuliah berhasil ditambahkan.');
    }

    public function show($id)
    {
        $this->noValidate();

        $this->id_tugas = $id;
        $tugas = ModelsTugas::find($id);

        // get all matkul
        $this->matkuls = Matkul::get();
        $this->matkul       = $tugas->matkul_id;
        $this->deskripsi    = ucfirst($tugas->deskripsi);
        $this->batas_waktu  = date('Y-m-d\TH:i', strtotime($tugas->batas_waktu));
        $this->selesai      = !$tugas->selesai ? $tugas->selesai : date('Y-m-d\TH:i', strtotime($tugas->selesai));
        $this->pertemuan_ke = $tugas->pertemuan_ke;
        $this->form         = 'edit';
    }

    public function update($id)
    {
        $this->validate();

        $tugas = ModelsTugas::find($id);

        $batasWaktu = new DateTime($this->batas_waktu);
        $tglSelesai = new DateTime($this->selesai);

        $batasWaktuCount = date('YmdHi', strtotime($this->batas_waktu));

        $tglSelesaiCount =  date('YmdHi', strtotime($this->selesai));


        if ($tglSelesaiCount > $batasWaktuCount) {
            // jika waktu telah habis
            $sisa = 0;
        } elseif ($tglSelesai->diff($batasWaktu)->days == 0) {
            // jika sisa beberapa jam
            $sisa = 1;
        } else {
            $sisa = 1;
        }

        if ($sisa == 0) {

            $this->selesai = null;

            $this->validate(
                [
                    'selesai' => 'required'
                ],
                ['required' => 'Tanggal selesai tidak boleh lebih besar dari batas waktu!']
            );
        } else {
            $tugas->matkul_id      = $this->matkul;
            $tugas->deskripsi      = strtolower($this->deskripsi);
            $tugas->batas_waktu    = $this->batas_waktu;
            $tugas->selesai        = $this->selesai;
            $tugas->pertemuan_ke   = $this->pertemuan_ke;
            $tugas->save();

            $this->hideForm();

            $this->showAlert('Mata Kuliah berhasil diubah.');
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

    public function showAlert($message)
    {
        $this->alert('success', $message, [
            'position'          =>  'top',
            'timer'             =>  1500,
            'toast'             =>  true,
            'showCancelButton'  =>  false,
            'showConfirmButton' =>  false
        ]);
    }

    public function confirmed()
    {
        ModelsTugas::destroy($this->id_tugas);

        $this->showAlert('Tugas berhasil dihapus.');
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
