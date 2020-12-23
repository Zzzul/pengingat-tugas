<?php

namespace App\Http\Livewire;

use App\Models\matkul as ModelsMatkul;
use App\Models\semester;
use Livewire\Component;

class Matkul extends Component
{
    public $form, $id_matkul, $name, $sks, $semesters, $semesters_id, $semester_id = '';

    protected $rules = [
        'name' => 'required',
        'sks' => 'required',
        'semester_id' => 'required',
    ];

    public function render()
    {
        $matkuls = ModelsMatkul::with('semester')->paginate(10);
        // get all semesters
        $this->semesters = Semester::get();
        return view('livewire.matkul', compact('matkuls'));
    }

    public function showForm($type)
    {
        $this->form = $type;

        if ($this->name) {
            $this->removeAllFields();
        }
    }

    public function hideForm()
    {
        $this->form = '';
        $this->removeAllFields();
        $this->validate([
            'name' => '',
            'sks' => '',
            'semester_id' => ''
        ]);
    }


    public function removeAllFields()
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

        session()->flash('message', 'Mata Kuliah berhasil ditambahkan.');
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

        session()->flash('message', 'Mata Kuliah berhasil diubah.');

        $this->hideForm();
    }

    public function destroy($id)
    {
        ModelsMatkul::destroy($id);
        session()->flash('message', 'Semester berhasil dihapus.');
    }
}
