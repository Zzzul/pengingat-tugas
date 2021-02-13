<?php

namespace App\Http\Livewire;

use App\Models\Matkul;
use App\Models\Tugas as ModelsTugas;
use DateTime;
use Livewire\Component;
use Livewire\WithPagination;

class Tugas extends Component
{

    use WithPagination;

    public $form, $id_tugas, $deskripsi, $batas_waktu, $tugas, $selesai, $pertemuan_ke, $matkul = '', $matkuls, $tugas_yg_ga_selesai;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'matkul'        => 'required',
        'deskripsi'     => 'required',
        'batas_waktu'   => 'required',
        'pertemuan_ke'  => 'required',
    ];

    public $search = '';
    public $page = 1;

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
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

        // echo json_encode($this->tugas_yg_ga_selesai);
        // die;


        $all_tugas = ModelsTugas::where('deskripsi', 'like', '%' . $this->search . '%')
            ->orWhere('batas_waktu', 'like', '%' . $this->search . '%')
            ->orWhere('selesai', 'like', '%' . $this->search . '%')
            ->with('matkul')
            ->orWhereHas('matkul', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('selesai', 'asc')
            ->paginate(5);

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
        $matkul->deskripsi      = $this->deskripsi;
        $matkul->batas_waktu    = $this->batas_waktu;
        $matkul->pertemuan_ke    = $this->pertemuan_ke;
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
        $this->deskripsi    = $tugas->deskripsi;
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
            $this->alert('error', 'Tanggal selesai tidak boleh lebih besar dari batas waktu!', [
                'position'          =>  'top',
                'timer'             =>  2500,
                'toast'             =>  true,
                'showCancelButton'  =>  false,
                'showConfirmButton' =>  false
            ]);

            $this->selesai = null;

            $this->validate([
                'selesai' => 'required'
            ]);
        } else {
            $tugas->matkul_id      = $this->matkul;
            $tugas->deskripsi      = $this->deskripsi;
            $tugas->batas_waktu    = $this->batas_waktu;
            $tugas->selesai        = $this->selesai;
            $tugas->pertemuan_ke   = $this->pertemuan_ke;
            $tugas->save();

            $this->hideForm();

            $this->showAlert('Mata Kuliah berhasil diubah.');
        }
    }

    public function destroy($id)
    {
        ModelsTugas::destroy($id);
        $this->showAlert('Mata Kuliah berhasil dihapus.');

        $this->hideForm();
        $this->emptyItems();
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
}
