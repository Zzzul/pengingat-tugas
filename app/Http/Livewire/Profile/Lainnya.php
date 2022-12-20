<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Traits\LivewireAlert;

class Lainnya extends Component
{
    use LivewireAlert;

    public function render()
    {
        return view('livewire.profile.lainnya');
    }

    public function logout()
    {
        $this->showAlert('success', 'Kamu berhasil logout!');

        Auth::logout();
        
        return redirect(route('home'));
    }
}
