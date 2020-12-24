<?php

namespace App\Http\Livewire;

use App\Models\Tugas as ModelsTugas;
use Livewire\Component;

class Tugas extends Component
{

    public $form;

    public function render()
    {
        $tugas = ModelsTugas::paginate(10);
        return view('livewire.tugas', compact('tugas'));
    }
}
