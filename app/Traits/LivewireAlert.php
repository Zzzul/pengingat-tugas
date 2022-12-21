<?php

namespace App\Traits;

use Jantinnerezo\LivewireAlert\LivewireAlert as Alert;

trait LivewireAlert
{
    use Alert;

    public function showAlert($type, $message)
    {
        $this->alert($type, $message, [
            'position'          =>  'top',
            'timer'             =>  2000,
            'toast'             =>  true,
            'showCancelButton'  =>  false,
            'showConfirmButton' =>  false
        ]);
    }
}
