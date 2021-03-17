<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class Login extends Component
{

    public $username, $password;

    protected $rules = [
        'username' => 'required',
        'password' => 'required',
    ];

    public function render()
    {
        return view('livewire.auth.login');
    }

    public function login()
    {
        $this->validate();

        $user = User::where(['username' => $this->username])->get();

        if (!$user->isEmpty() && Auth::attempt(['username' => $this->username, 'password' => $this->password])) {

            $this->flash('success', 'Kamu berhasil login!', [
                'position' =>  'top',
                'timer'    =>  1500,
                'toast'    =>  true,
            ]);

            $previousUrl = url()->previous();

            if ($previousUrl == 'home' || $previousUrl == 'login' || $previousUrl == 'register') {
                redirect('/home');
            } else {
                redirect('' . $previousUrl . '');
            }
        } else {
            $this->alert('error', 'Username atau password salah!', [
                'position' =>  'top',
                'timer'    =>  1500,
                'toast'    =>  true,
            ]);
        }
    }
}
