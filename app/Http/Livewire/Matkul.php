<?php

namespace App\Http\Livewire;

use App\Models\matkul as ModelsMatkul;
use App\Models\semester;
use Livewire\Component;

class Matkul extends Component
{
    public $form, $id_matkul, $name, $sks, $semester_id;

    protected $rules = [
        'name' => 'required',
        'sks' => 'required',
        'semester_id' => 'required',
    ];

    public function render()
    {
        $matkuls = ModelsMatkul::with('semester')->paginate(10);
        $semesters = Semester::get();
        // echo json_encode($matkuls);
        // die;
        return view('livewire.matkul', compact('matkuls', 'semesters'));
    }

    public function showForm($type)
    {
        $this->form = $type;
    }

    public function hideForm()
    {
        $this->form = '';
        $this->name = '';
        $this->sks = '';
        $this->semester_id = '';
        $this->validate([
            'name' => '',
            'sks' => '',
            'semester_id' => ''
        ]);
    }
}
