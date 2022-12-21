<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use App\Traits\LivewireAlert;
use App\Models\Semester;

class Register extends Component
{
    use LivewireAlert;

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

        $user = User::create([
            'name'      => $this->name,
            'username'  => $this->username,
            'email'     => $this->email,
            'password'  => bcrypt($this->password),
        ]);

        $user->assignRole('mahasiswa');
        $user->givePermissionTo([
            'tugas',
            'semester',
            'mata kuliah',
            'edit profile',
            'ganti password'
        ]);

        $semesters = [];

        for ($i = 1; $i <= 8; ++$i) {
            $semesters[] = [
                'semester_ke' => $i,
                'user_id' => $user->id,
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString()
            ];
        }
        
        Semester::insert($semesters);

        $this->showAlert('success', 'Akun berhasil didaftarkan silahkan login!');

        return redirect(route('login'));
    }
}
