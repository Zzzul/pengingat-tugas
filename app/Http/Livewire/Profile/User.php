<?php

namespace App\Http\Livewire\Profile;

use App\Models\User as ModelsUser;
use Livewire\Component;

class User extends Component
{
    public $username, $name, $email;


    public function mount()
    {
        $this->username = auth()->user()->username;
        $this->name = auth()->user()->name;
        $this->email = auth()->user()->email;
    }

    public function render()
    {
        return view('livewire.profile.user');
    }

    public function update()
    {
        $this->validate([
            'name'  => 'required|min:5,max:25',
            'email' => 'required|email|unique:users,email,' . auth()->user()->id,
        ]);

        $user = ModelsUser::find(auth()->user()->id);
        $user->name = $this->name;
        $user->email = $this->email;
        $user->save();

        $this->flash('success', 'Profile berhasil diubah!', [
            'position' =>  'top',
            'timer'    =>  1000,
            'toast'    =>  true,
        ]);
        redirect(route('user-profile'));
    }
}
