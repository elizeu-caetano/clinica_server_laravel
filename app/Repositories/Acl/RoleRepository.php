<?php

namespace App\Repositories\Acl;

use App\Http\Resources\Acl\PermissionResource;
use App\Http\Resources\Acl\RoleResource;
use App\Models\Acl\Permission;
use App\Models\Acl\Role;
use App\Repositories\Acl\Contracts\RoleRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleRepository implements RoleRepositoryInterface
{
    private $repository;

    public function __construct(Role $role)
    {
        $this->repository = $role;
    }

    public function search(array $data)
    {
        try {
            $roles = $this->repository->with('contractor')
                ->where('active', $data['active'])
                ->where('deleted', false)
                ->where(function ($query) {
                    if (Auth::user()->contractor_id != 1) {
                        $query->where('contractor_id', Auth::user()->contractor_id);
                    }
                })
                ->where(function ($query) use ($data) {
                    $query->where('name', 'LIKE', '%'.$data['search'].'%')
                        ->orWhere('description', 'LIKE', '%'.$data['search'].'%');
                })
                ->orderBy('contractor_id')
                ->orderBy('name')
                ->get();

            return ['status' => true, 'data' => RoleResource::collection($roles)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function store(array $data)
    {
        try {

            $role = $this->repository->create($data);

            return ['status' => true, 'message' => 'O Papel foi cadastrado.', 'data' => new RoleResource($role)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Papel não foi cadastrado.', 'error' => $th->getMessage()];
        }
    }

    public function show($uuid)
    {
        try {
            $role = $this->repository->where('uuid', $uuid)->first();

            return ['status' => true, 'data' => new RoleResource($role)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update(array $data)
    {
        try {
            $role = $this->repository->where('uuid', $data['uuid'])->first();
            $role->update($data);

            return ['status' => true, 'data' => new RoleResource($role), 'message' => 'O Papel foi editado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Papel não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function activate($uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['active' => true]);

            $this->repository->where('uuid', $uuid)->first()->activateAudit('Papéis');

            return ['status' => true, 'message' => 'O Papel foi ativado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Papel não foi ativado.', 'error' => $th->getMessage()];
        }
    }

    public function inactivate($uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['active' => false]);

            $this->repository->where('uuid', $uuid)->first()->inactivateAudit('Papéis');

            return ['status' => true, 'message' => 'O Papel foi inativado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Papel não foi inativado.', 'error' => $th->getMessage()];
        }
    }

    public function deleted($uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['deleted' => true]);

            $this->repository->where('uuid', $uuid)->first()->deletedAudit('Papéis');

            return ['status' => true, 'message' => 'O Papel foi deletado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Papel não foi deletado.', 'error' => $th->getMessage()];
        }
    }

    public function recover($uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['deleted' => false]);

            $this->repository->where('uuid', $uuid)->first()->recoverAudit('Papéis');

            return ['status' => true, 'message' => 'O Papel foi recuperado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Papel não foi recuperado.', 'error' => $th->getMessage()];
        }
    }

    public function destroy($uuid)
    {
        try {
            $role = $this->repository->where('uuid', $uuid)->first();

            $role->permissions()->detach();
            $role->delete();

            return ['status' => true, 'message' => 'O Papel foi excluído.'];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'O Papel não foi excluído', 'error' => $th->getMessage()];
        }
    }

    public function rolePermissions($uuid)
    {
        try {
            $role = Role::where('uuid', $uuid)->first();
            $permissionsRolesId = $role->permissions()->pluck('id')->toArray();
            $permissionsRole = $role->permissions()->get();

            if (Auth::user()->isSuperAdmin()) {
                $notPermissionsRole = Permission::whereNotIn('id', $permissionsRolesId)->get();
            } else {
                if ($role->admin) {
                    return ['status' => false, 'message' => 'O Papel de Administrador não pode ser alterado.', 'permissionsRole' => [], 'notPermissionsRole' => []];
                }

                $roleAdmin = $this->repository->where('contractor_id', $role->contractor_id)->first();
                $notPermissionsRole = $roleAdmin->permissions()->whereNotIn('id', $permissionsRolesId)->get();
            }

            return ['status' => true, 'permissionsRole' => $permissionsRole, 'notPermissionsRole' => $notPermissionsRole];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'Não foi possível carregar as permissões', 'error' => $th->getMessage()];
        }
    }

    public function attachPermissions(array $data)
    {
        try {
            $role = $this->repository->where('uuid', $data['uuid'])->first();
            $rolesId = $role->permissions()->pluck('id');

            $role->permissions()->attach($data['permissions']);

            $permissionsRole = $role->permissions()->get();
            if (Auth::user()->isSuperAdmin()) {
                $notPermissionsRole = Permission::whereNotIn('id', $rolesId)->get();
            }

            return ['status' => true, 'message' => 'Permissões adicionadas.', 'permissionsRole' => PermissionResource::collection($permissionsRole), 'notPermissionsRole' => PermissionResource::collection($notPermissionsRole)];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'As permissões não foi adicionadas.', 'error' => $th->getMessage()];
        }
    }

    public function detachPermissions(array $data)
    {
        try {
            $role = $this->repository->where('uuid', $data['uuid'])->first();

            $role->permissions()->detach($data['permissions']);

            return ['status' => true, 'message' => 'Permissões excluidas.'];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'As Permissões não foi excluidas.', 'error' => $th->getMessage()];
        }
    }
}
