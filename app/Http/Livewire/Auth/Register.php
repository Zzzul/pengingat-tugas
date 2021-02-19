<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Livewire\Component;

class Register extends Component
{
    public $name, $username, $email, $password, $password_confirmation;

    protected $rules = [
        'email'     => 'required|email|unique:users,email',
        'username'  => 'required|string|unique:users,username|alpha_num|min:5,max:25',
        'name'      => 'required|min:5,max:25',
        'password'  => 'required|min:8|confirmed',
    ];

    public function render()
    {
        return view('livewire.auth.register');
    }

    public function register()
    {
        $this->validate();

        User::create([
            'name'      => $this->name,
            'username'  => $this->username,
            'email'     => $this->email,
            'password'  => bcrypt($this->password),
        ]);

        $this->flash('success', 'Akun berhasil didaftarkan silahkan login!', [
            'position'  =>  'top',
            'timer'     =>  1500,
            'toast'     =>  true,
        ]);

        return redirect(route('login'));
    }
}
