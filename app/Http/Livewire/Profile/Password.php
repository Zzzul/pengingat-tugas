<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Password extends Component
{

    public $current_password, $password, $password_confirmation;

    public function render()
    {
        return view('livewire.profile.password');
    }


    public function update()
    {
        if (auth()->user()->hasPermissionTo('ganti password')) {
            $this->validate([
                'current_password' => 'required',
                'password' => 'required|min:8|confirmed',
                'password_confirmation' => 'required',
            ]);

            if (Hash::check($this->current_password, auth()->user()->password)) {
                auth()->user()->update([
                    'password' => bcrypt($this->password)
                ]);

                $this->flash('success', 'Password berhasil diubah, silahkan login ulang!', [
                    'position'  =>  'top',
                    'timer'     =>  1500,
                    'toast'     =>  true,
                ]);

                Auth::logout();

                return redirect(route('home'));
            } else {
                $this->showAlert('error', 'Password lama salah!');
            }
        } else {
            $this->showAlert('error', 'Kamu tidak bisa mengganti password!');
        }
    }


    public function showAlert($type, $message)
    {
        $this->alert($type, $message, [
            'position'          =>  'top',
            'timer'             =>  1500,
            'toast'             =>  true,
            'showCancelButton'  =>  false,
            'showConfirmButton' =>  false
        ]);
    }
}
