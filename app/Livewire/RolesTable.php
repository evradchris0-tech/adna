<?php

namespace App\Livewire;

use App\Permissions\PermissionsModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesTable extends Component
{
    use WithPagination;


    public string $term = '';
    public string $orderField = "name";
    public string $orderDirection = "ASC";
    public string $name = "";
    public bool $showModal = false;
    public bool $isSending = false;
    public bool $isDeleting = false;
    public bool $showDeleteModal = false;
    public int $numPerPage = 30;
    public $id = null;
    public $droppedPermissions= [];
    protected $listeners = ['dataSelected', 'sendData'];

    public function render()
    {
        $roles = Role::with('permissions')->where("name", "LIKE", "%{$this->term}%")
        ->orderBy($this->orderField,$this->orderDirection)
        ->get();
        $page = Paginator::resolveCurrentPage() ?: 1;
        $roles = new LengthAwarePaginator(
            $roles->forPage($page, $this->numPerPage)->all(),
            $roles->count(),
            $this->numPerPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );
        $permissions = Permission::all()->toArray();
        return view('roles.livewire.roles-table', compact('roles', 'permissions'));
    }

    public function onSubmit(){

        $data = $this->validate(
            [
                'name' => 'required|unique:roles,name,'.$this->id,
            ]
        );
        $this->isSending = true;
        if ($this->id) {
            $role = Role::findById($this->id);
            $role->update(['name' => $data['name']]);
            $role->syncPermissions($this->droppedPermissions);
        }else{
            // insert in data base
            $role = Role::create(['name' => $data['name']]);
            $role->syncPermissions($this->droppedPermissions);
            $role->save();
        }
        $this->showModal = !$this->showModal;
        $this->dispatch("resetData",$this->droppedPermissions);
        $this->reset(['name','id','droppedPermissions']);
        $this->isSending = false;
    }

    public function setOrderField(string $field){
        if ($field == $this->orderField) {
            $this->orderDirection = $this->orderDirection  == 'ASC' ? 'DESC' : 'ASC';
        } else {
            $this->orderField = $field;
            $this->reset('orderDirection');
        }
    }

    public function paginationView()
    {
        return 'components.pagination';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete()
    {
        $role = Role::with('users')->find($this->id);
        if (count($role->users) > 0) {
            session()->flash('error',"impossible de supprimer ce role car il est associé à des utilisateurs !");
            $this->reset(['id']);

        }else{
            $role->delete();
            session()->flash('message',"role supprimé avec success !");
        }
        $this->showDeleteModal = !$this->showDeleteModal;
        return redirect()->route('roles.index');
    }

    public function onShowModal($id = null){
        if ($this->isSending) {
            return;
        }
        if ($id != null) {
            $role = Role::with(["permissions"])->find($id);
            $this->name = $role->name;
            $this->id = $id;
            $this->droppedPermissions = array_map(function ($it){
                return $it['id'];
            }, $role->permissions->toArray());
        }else{
            $this->reset(['name','id','droppedPermissions']);
        }
        $this->dispatch("resetData",$this->droppedPermissions);
        $this->showModal = !$this->showModal;
        $this->resetErrorBag();
    }

    public function onShowDeleteModal($id=null){
        if ($this->isDeleting) {
            return;
        }
        $this->id = $id;
        $this->showDeleteModal = !$this->showDeleteModal;
    }
}
