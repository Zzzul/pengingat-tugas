<?php

namespace App\Traits;

trait LivewireAlert
{
    public function showAlert($type, $message)
    {
        $this->alert($type, $message, [
            'position'          =>  'top',
            'timer'             =>  1500,
            'toast'             =>  true,
            'showCancelButton'  =>  false,
            'showConfirmButton' =>  false
        ]);
    }
}
