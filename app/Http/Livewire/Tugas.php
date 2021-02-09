<?php

namespace App\Http\Livewire;

use App\Models\Matkul;
use App\Models\Tugas as ModelsTugas;
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


        $all_tugas = ModelsTugas::with('matkul')->orderBy('batas_waktu', 'desc')->paginate(5);

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

        $matkul = ModelsTugas::find($id);
        $matkul->matkul_id      = $this->matkul;
        $matkul->deskripsi      = $this->deskripsi;
        $matkul->batas_waktu    = $this->batas_waktu;
        $matkul->selesai        = $this->selesai;
        $matkul->pertemuan_ke   = $this->pertemuan_ke;
        $matkul->save();

        $this->hideForm();

        $this->showAlert('Mata Kuliah berhasil diubah.');
    }

    public function destroy($id)
    {
        ModelsTugas::destroy($id);
        $this->showAlert('Mata Kuliah berhasil dihapus.');
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
