<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Traits\LivewireAlert;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $form, $id_user, $name, $email, $username, $all_roles, $user_roles, $user_permissions, $not_user_permissions, $permissions = [];

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
        // $this->user_roles = 1;
        $this->fill(request()->only('search', 'page'));
    }

    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('username', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            // ->orWhereHas('role,name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'asc')->paginate($this->paginate_per_page);

        // $this->all_roles = Role::get();

        return view('livewire.user-list', compact('users'));
    }

    public function show($id)
    {
        // hilangkan error karena validasi
        $this->noValidate();

        $this->id_user = $id;

        $user = User::findOrFail($id);

        $this->all_roles = Role::get();

        // $this->user_roles = $user->getRoleNames()->isEmpty() ? '' : $user->getRoleNames();

        // $user->getRoleNames()->isEmpty() ? $this->user_roles = '' : $this->user_roles = Role::where('name', $user->getRoleNames()[0])->get();

        // $this->user_roles = Role::where('name', $user->getRoleNames()[0])->get();

        if ($user->getRoleNames()->isEmpty()) {
            $this->user_roles = '';
        } else {
            $this->user_roles = Role::where('name', $user->getRoleNames()[0])->get();
            $this->user_roles = $this->user_roles[0]['id'];
        }

        // dd($this->user_roles);

        $this->all_roles = Role::all();

        $permissions_id = [];
        foreach ($user->permissions as $key => $value) {
            $permissions_id[] = $value->id;
            // $this->permissions[] = $value->name;
        }

        $this->user_permissions = Permission::whereIn('id', $permissions_id)->get();
        $this->not_user_permissions = Permission::whereNotIn('id', $permissions_id)->get();


        $this->permissions = $permissions_id;

        // dd($this->permissions);

        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->form = 'edit';
    }

    public function update($id)
    {
        $this->validate([
            'name'  => 'required|min:4,max:25',
            // 'email' => 'required|email|unique:users,email,' . auth()->user()->id,
            'user_roles' => 'required',
            'permissions' => 'required',
        ]);

        $user = User::findOrFail($id);
        $user_roles = Role::find($this->user_roles);
        $role_name = $user->getRoleNames()[0]; // result = 'admin' or 'demo'
        $permission_name = $user->permissions;

        // dd($this->permissions);

        if (!$user->getRoleNames()->isEmpty()) {
            $user->removeRole($role_name);
            $user->revokePermissionTo($permission_name);
        }


        if (isset($this->permissions)) {
            foreach ($this->permissions as $key => $value) {
                $user->givePermissionTo($this->permissions[$key]);
                $user->assignRole($user_roles);
            }
        }

        // dd($this->permissions);

        $user->name = $this->name;
        $user->email = $this->email;
        $user->save();

        $this->showAlert('success', 'User berhasil diubah.');

        // $this->user_permissions = '';
        // $this->not_user_permissions = '';
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
        // $this->name = '';
        // $this->email = '';
        $this->emptyItems();
        $this->noValidate();
        // $this->user_permissions = [];
        // $this->not_user_permissions = [];
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
