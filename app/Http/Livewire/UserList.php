<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Traits\LivewireAlert;
// use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $form, $id_user, $name, $email, $username;

    public $search = '';
    public $page = 1;
    public $paginate_per_page = 5;

    protected $paginationTheme = 'bootstrap';


    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $listeners = [
        'confirmed',
        'cancelled',
    ];

    public function mount()
    {
        $this->fill(request()->only('search', 'page'));
    }

    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('username', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')->paginate($this->paginate_per_page);
        return view('livewire.user-list', compact('users'));
    }

    public function show($id)
    {
        $this->noValidate();

        $this->id_user = $id;

        $user = User::findOrFail($id);

        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->form = 'edit';
    }

    public function update($id)
    {
        $this->validate([
            'name'  => 'required|min:5,max:25',
            'email' => 'required|email|unique:users,email,' . auth()->user()->id,
        ]);

        $user = User::find($id);
        $user->name = $this->name;
        $user->email = $this->email;
        $user->save();

        $this->showAlert('success', 'User berhasil diubah.');


        $this->hideForm();
    }

    public function emptyItems()
    {
        $this->name = '';
        $this->email = '';
    }

    public function hideForm()
    {
        $this->form = '';
        $this->name = '';
        $this->email = '';
        $this->noValidate();
    }

    public function noValidate()
    {
        $this->validate([
            'name' => '',
            'email' => ''
        ]);
    }

    public function confirmed()
    {
        $user =  User::findOrFail($this->id_user);
        if ($user->id != auth()->user()->id) {

            // jika tidak terdapat relasi pada user
            try {
                $user->destroy($user->id);
                $this->showAlert('success', 'User berhasil dihapus.');
            } catch (\Exception $ex) {
                $this->showAlert('error', 'Tidak dapat dihapus karena terdapat semester pada user : ' . $user->name);
            }
        } else {
            $this->showAlert('error', 'User tidak dapat dihapus karena sedang kamu pakai sekarang!.');
        }

        $this->id_user = '';
        $this->hideForm();
    }

    public function cancelled()
    {
        $this->id_user = '';
        $this->alert('error', 'Dibatalkan', [
            'position'          =>  'top',
            'timer'             =>  1500,
            'toast'             =>  true,
            'showCancelButton'  =>  false,
            'showConfirmButton' =>  false
        ]);
    }

    public function triggerConfirm($id)
    {
        $this->id_user = $id;
        $this->confirm('Yakin ingin menghapus data ini?', [
            'toast' => false,
            'position' => 'center',
            'confirmButtonText' =>  'ya',
            'cancelButtonText' =>  'Batal',
            'onConfirmed' => 'confirmed',
            'onCancelled' => 'cancelled'
        ]);
    }
}
