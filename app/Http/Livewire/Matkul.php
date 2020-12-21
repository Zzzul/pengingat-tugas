<?php

namespace App\Http\Livewire;

use App\Models\matkul as ModelsMatkul;
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
        $matkuls = ModelsMatkul::paginate(10);
        return view('livewire.matkul', compact('matkuls'));
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
