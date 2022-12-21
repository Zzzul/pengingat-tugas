<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use App\Traits\LivewireAlert;

class Login extends Component
{
    use LivewireAlert;

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

            // $this->showAlert('success', 'Kamu berhasil login!');

            $previousUrl = url()->previous();

            if ($previousUrl == 'login') {
                redirect('/home');
            } else {
                redirect('' . $previousUrl . '');
            }
        } else {
            $this->showAlert('error', 'Username atau password salah!');
        }
    }
}
