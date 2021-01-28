<?php

namespace App\Http\Livewire;

use App\Models\semester as ModelsSemester;
use Livewire\Component;
use Livewire\WithPagination;

class Semester extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $form, $id_semester, $semester_ke, $aktif_smt, $select_semesters = [];

    protected $rules = [
        'semester_ke' => 'required',
    ];

    public function render()
    {
        $this->select_semesters = ModelsSemester::get();

        $this->aktif_smt = ModelsSemester::select('id', 'semester_ke')->where('aktif_smt', 1)->first();

        $semesters = ModelsSemester::paginate(5);
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

        $this->showAlert('Semester berhasil ditambahkan.');

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

        $this->showAlert('Semester berhasil diubah.');

        $this->hideForm();
    }


    public function destroy($id)
    {
        ModelsSemester::destroy($id);
        $this->showAlert('Semester berhasil dihapus.');
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


    public function setAktifSmt($id)
    {

        if ($id != $this->aktif_smt['id']) {
            $semester_aktif = ModelsSemester::find($id);
            $semester_aktif->aktif_smt = 1;
            $semester_aktif->save();

            $semester = ModelsSemester::find($this->aktif_smt['id']);
            $semester->aktif_smt = null;
            $semester->save();

            $this->showAlert('Semester aktif berhasil diubah.');
        }
    }
}
