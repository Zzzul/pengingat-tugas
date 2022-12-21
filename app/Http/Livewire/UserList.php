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

    public $form, $id_user, $name, $email, $username, $all_roles, $user_roles, $user_permissions, $not_user_permissions, $permissions = [], $new_permissions = '';

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
        $this->permissions = [];
    }

    public function render()
    {
        $users = User::with('permissions')->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('username', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhereHas('roles', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })->orderBy('created_at', 'asc')
            ->paginate($this->paginate_per_page);

        return view('livewire.user-list', compact('users'));
    }

    public function show($id)
    {
        $this->noValidate();

        $this->id_user = $id;

        $user = User::findOrFail($id);

        $this->all_roles = Role::get();

        if ($user->getRoleNames()->isEmpty()) {
            $this->user_roles = '';
        } else {
            $this->user_roles = Role::where('name', $user->getRoleNames()[0])->get();
            $this->user_roles = $this->user_roles[0]['id'];
        }

        $this->all_roles = Role::all();

        $permissions_id = [];
        foreach ($user->permissions as $key => $value) {
            $permissions_id[] = $value->id;
        }

        $this->user_permissions = Permission::whereIn('id', $permissions_id)->get();
        $this->not_user_permissions = Permission::whereNotIn('id', $permissions_id)->get();

        $this->permissions['id'] = $permissions_id;

        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->form = 'edit';
    }

    public function update($id)
    {
        $this->validate([
            'name' => 'required|min:4,max:25',
            'email' => 'required|unique:users,email,' . $this->id_user,
            'user_roles' => 'required',
            'permissions' => 'array|required',
        ]);

        $user = User::findOrFail($id);
        $user_roles = Role::find($this->user_roles);
        $user->getRoleNames()->isEmpty() ? $role_name = '' : $role_name = $user->getRoleNames()[0];
        $permission_name = $user->permissions;

        if (!$user->getRoleNames()->isEmpty()) {
            $user->removeRole($role_name);
            $user->revokePermissionTo($permission_name);
        }

        // assign new role
        $user->assignRole($user_roles);
        if (isset($this->permissions['id'])) {
            foreach ($this->permissions['id'] as $value) {
                $user->givePermissionTo($value);
            }
        }

        // jika role = admin maka ceklis permission user
        if ($user_roles['name'] == 'admin') {
            // 6 = permission user
            $user->givePermissionTo(6);
        } else {
            $user->revokePermissionTo(6);
        }

        $user->name = $this->name;
        $user->email = $this->email;
        $user->save();

        $this->hideForm();
        $this->showAlert('success', 'User berhasil diubah.');
    }

    public function emptyItems()
    {
        $this->name = '';
        $this->email = '';
    }

    public function hideForm()
    {
        $this->form = '';
        $this->emptyItems();
        $this->noValidate();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function noValidate()
    {
        $this->validate([
            'name'           => '',
            'email'          => '',
            'user_roles'     => '',
            'permissions.id' => '',
        ]);
    }

    public function confirmed()
    {
        $user = User::findOrFail($this->id_user);
        if ($user->id != auth()->user()->id) {

            // jika tidak terdapat relasi pada user
            try {
                $user->destroy($user->id);
                $this->showAlert('success', 'User berhasil dihapus.');
            } catch (\Exception $ex) {
                $this->showAlert('error', 'Tidak dapat dihapus karena terdapat semester pada user : ' . $user->name);
                $user->assignRole('user');
                $user->givePermissionTo(['tugas', 'semester', 'matkul', 'edit profile', 'ganti password']);
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
            'toast'             => false,
            'position'          => 'center',
            'confirmButtonText' =>  'ya',
            'cancelButtonText'  =>  'Batal',
            'onConfirmed'       => 'confirmed',
            'onCancelled'       => 'cancelled'
        ]);
    }
}
