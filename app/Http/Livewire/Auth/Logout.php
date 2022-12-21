<?php

namespace App\Http\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    public $type;

    public function render()
    {
        return view('livewire.auth.logout');
    }

    public function logout()
    {
        // $this->flash('success', 'Kamu berhasil logout!', [
        //     'position'          =>  'top',
        //     'timer'             =>  1500,
        //     'toast'             =>  true,
        // ]);
        
        Auth::logout();
        
        return redirect(route('home'));
    }
}
