<?php

namespace App\Repositories\Acl;

use App\Http\Resources\Acl\PermissionResource;
use App\Models\Acl\Permission;
use App\Repositories\Acl\Contracts\PermissionRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class PermissionRepository implements PermissionRepositoryInterface {

    private $repository;

    public function __construct(Permission $permission)
    {
        $this->repository = $permission;
    }

    public function search(array $data)
    {
        try {
            $permissions = $this->repository->where(function ($query) use ($data) {
                                $query->where('name', 'LIKE', '%'.$data['search'].'%')
                                ->orWhere('permission', 'LIKE', '%'.$data['search'].'%');
                            })
                            ->get();

            return ['status' => true, 'data' => PermissionResource::collection($permissions)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function store(array $data, array $plan_ids, bool $developer)
    {
        try {
                $permission = $this->repository->create($data);

                if ($plan_ids) {
                    $permission->plans()->attach($plan_ids);
                }

                if (!$developer) {
                    $this->attachPermissionRole($permission);
                }

                Cache::forget('permissions_provider');

            return ['status' => true, 'message' => 'A Permissão foi cadastrada.', 'data' => new PermissionResource($permission)];

        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'A Permissão não foi cadastrada.', 'error' => $th->getMessage()];
        }
    }

    public function storeBatch(array $permissions, string $name, string $name_permission, array $plan_ids, bool $developer)
    {
        try {
            for ($i=0; $i < count($permissions); $i++) {

                foreach ($permissions[$i] as $key => $value) {
                    $data['name'] = $value . ' ' . $name;
                    $data['permission'] = $key . '_' . $name_permission;
                }

                $permission = $this->repository->create($data);

                if ($plan_ids) {
                    $permission->plans()->attach($plan_ids);
                }

                if (!$developer) {
                    $this->attachPermissionRole($permission);
                }
            }

            return ['status' => true, 'message' => 'A Permissão foi cadastrada.', 'data' => new PermissionResource($permission)];

        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'A Permissão não foi cadastrada.', 'error' => $th->getMessage()];
        }
    }

    private function attachPermissionRole($permission)
    {
        $plans = $permission->plans()->first();
        $contractors = $plans->contractors()->get();

        $rolesId = [];
        foreach ($contractors as $contractor) {
            $roles = $contractor->roles()->where('admin', true)->get();
            foreach ($roles as $role) {
                 array_push($rolesId, $role->id);
            }
        }

        $permission->roles()->attach($rolesId);
    }

    public function show(int $id)
    {
        try {

            $permission = $this->repository->find($id);

            return ['status' => true, 'data' => new PermissionResource($permission)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update(array $data)
    {
        try {

            $permission = $this->repository->find($data['id']);
            $permission->update($data);

            Cache::forget('permissions_provider');

            return ['status' => true, 'message' => 'A Permissão foi editada.', 'data' => new PermissionResource($permission)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Permissão não foi editada.', 'error' => $th->getMessage()];
        }
    }

    public function destroy(int $id)
    {
        try {
            $permission = $this->repository->find($id);

            $permission->plans()->detach();
            $permission->roles()->detach();

            $permission->delete();

            Cache::forget('permissions_provider');

            return ['status'=> true, 'message'=> 'A Permissão foi excluída.'];

        } catch (\Throwable $th) {

            return ['status'=> false, 'message'=> 'A Permissão não foi excluído', 'error'=> $th->getMessage()];
        }
    }
}
