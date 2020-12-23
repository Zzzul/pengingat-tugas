<?php

namespace App\Http\Livewire;

use App\Models\matkul as ModelsMatkul;
use App\Models\semester;
use Livewire\Component;
use Livewire\WithPagination;

class Matkul extends Component
{
    use WithPagination;

    public $form, $id_matkul, $name, $sks, $semesters, $semester_id = '';

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name' => 'required',
        'sks' => 'required',
        'semester_id' => 'required',
    ];

    public function render()
    {
        $matkuls = ModelsMatkul::with('semester')->paginate(5);

        // get all semesters
        $this->semesters = Semester::get();
        return view('livewire.matkul', compact('matkuls'));
    }

    public function showForm($type)
    {
        $this->form = $type;

        if ($this->name) {
            $this->emptyItems();
        }
    }

    public function hideForm()
    {
        $this->form = '';
        $this->emptyItems();
        $this->validate([
            'name' => '',
            'sks' => '',
            'semester_id' => ''
        ]);
    }


    public function emptyItems()
    {
        $this->name = '';
        $this->sks = '';
        $this->semester_id = '';
    }

    public function store()
    {
        $this->validate();

        $matkul = new ModelsMatkul;
        $matkul->name = $this->name;
        $matkul->sks = $this->sks;
        $matkul->semester_id = $this->semester_id;
        $matkul->save();

        $this->hideForm();

        $this->showAlert('Mata Kuliah berhasil ditambahkan.');
    }

    public function show($id)
    {
        $this->id_matkul = $id;
        $matkul = ModelsMatkul::find($id);

        // get all semesters
        $this->semesters = Semester::get();

        $this->name = $matkul->name;
        $this->sks = $matkul->sks;
        $this->semester_id = $matkul->semester_id;
        $this->form = 'edit';
    }

    public function update($id)
    {
        $this->validate();

        $matkul = ModelsMatkul::find($id);
        $matkul->name = $this->name;
        $matkul->sks = $this->sks;
        $matkul->semester_id = $this->semester_id;
        $matkul->save();

        $this->hideForm();

        $this->showAlert('Mata Kuliah berhasil diubah.');
    }

    public function destroy($id)
    {
        ModelsMatkul::destroy($id);
        $this->showAlert('Mata Kuliah berhasil dihapus.');
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
