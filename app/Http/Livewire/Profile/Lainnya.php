<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Lainnya extends Component
{
    public function render()
    {
        return view('livewire.profile.lainnya');
    }

    public function logout()
    {
        $this->flash('success', 'Kamu berhasil logout!', [
            'position'          =>  'top',
            'timer'             =>  1500,
            'toast'             =>  true,
        ]);
        Auth::logout();
        return redirect(route('home'));
    }
}