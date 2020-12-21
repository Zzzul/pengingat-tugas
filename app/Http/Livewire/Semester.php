<?php

namespace App\Http\Livewire;

use App\Models\semester as ModelsSemester;
use Livewire\Component;

class Semester extends Component
{

    public $form, $id_semester, $semester_ke;

    protected $rules = [
        'semester_ke' => 'required',
    ];

    public function render()
    {
        $semesters = ModelsSemester::paginate(20);
        return view('livewire.semester', compact('semesters'));
    }

    public function showForm($type)
    {
        $this->form = $type;
    }

    public function hideForm()
    {
        $this->form = '';
        $this->semester_ke = '';
        $this->validate([
            'semester_ke' => ''
        ]);
    }


    public function store()
    {
        $this->validate();

        ModelsSemester::create([
            'semester_ke' => $this->semester_ke,
        ]);

        session()->flash('message', 'Semester berhasil ditambahkan.');

        $this->semester_ke = '';
        $this->form = '';
    }


    public function show($id)
    {
        $this->id_semester = $id;
        $semester = ModelsSemester::find($id);
        $this->semester_ke = $semester->semester_ke;
        $this->form = 'edit';
    }


    public function update($id)
    {
        $this->validate();

        $semester = ModelsSemester::find($id);
        $semester->semester_ke = $this->semester_ke;
        $semester->save();

        session()->flash('message', 'Semester berhasil diubah.');

        $this->id_semester = '';
        $this->semester_ke = '';
    }


    public function destroy($id)
    {
        ModelsSemester::destroy($id);
        session()->flash('message', 'Semester berhasil dihapus.');
    }
}
