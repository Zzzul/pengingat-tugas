<?php

namespace App\Http\Livewire\Profile;

use App\Models\User;
use Livewire\Component;
use App\Traits\LivewireAlert;

class UserInformation extends Component
{
    use LivewireAlert;

    public $username, $name, $email;

    public function mount()
    {
        $this->username = auth()->user()->username;
        $this->name = auth()->user()->name;
        $this->email = auth()->user()->email;
    }

    public function render()
    {
        return view('livewire.profile.user-information');
    }

    public function update()
    {
        $this->validate([
            'name'  => 'required|min:5,max:25',
            'email' => 'required|email|unique:users,email,' . auth()->user()->id,
        ]);

        $user = User::find(auth()->user()->id);
        $user->name = $this->name;
        $user->email = $this->email;
        $user->save();

        redirect(route('user-profile'));
        $this->showAlert('success', 'Profile berhasil diubah!');
    }
}
