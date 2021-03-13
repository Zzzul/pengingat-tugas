<?php

namespace App\Traits;

trait LivewireAlert
{
    public function showAlert($type, $message)
    {`
        $this->alert($type, $message, [
            'position'          =>  'top',
            'timer'             =>  2000,
            'toast'             =>  true,
            'showCancelButton'  =>  false,
            'showConfirmButton' =>  false
        ]);
    }
}
