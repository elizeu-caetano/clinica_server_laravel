<?php

namespace App\Services\Acl;

use App\Repositories\Acl\Contracts\PermissionRepositoryInterface;
use Illuminate\support\Str;

class PermissionService
{
    protected $permissionService;

    public function __construct(PermissionRepositoryInterface $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function search(object $request)
    {
        $data = $request->all();
        $data['active'] = !$request->active ? false : true;

        return $this->permissionService->search($data);
    }

    public function store(object $request)
    {
        $developer = $request->developer ? true : false;
        if ($request->lote) {
            $permissions = $this->createData($request->listPermission);
            return $this->permissionService->storeBatch($permissions, $request->name, $request->permission, $request->plan_ids, $developer);

        } else {
            $data['name'] = $request->name;
            $data['permission'] = $request->permission;

            return $this->permissionService->store($data, $request->plan_ids, $developer);
        }
    }

    private function createData($permissions)
    {
        $permission = [];
        foreach ($permissions as $value) {
            if ($value['description'] == 'Listar') {
                array_push($permission, ['search' => 'Listar']);
            } else if ($value['description'] == 'Cadastrar') {
                array_push($permission, ['store' => 'Cadastrar']);
            } else if ($value['description'] == 'Visualizar') {
                array_push($permission, ['show' => 'Visualizar']);
            } else if ($value['description'] == 'Editar') {
                array_push($permission, ['update' => 'Editar']);
            } else if ($value['description'] == 'Ativar') {
                array_push($permission, ['activate' => 'Ativar']);
            } else if ($value['description'] == 'Inativar') {
                array_push($permission, ['inactivate' => 'Inativar']);
            } else if ($value['description'] == 'Excluir') {
                array_push($permission, ['destroy' => 'Excluir']);
            } else if ($value['description'] == 'Apagar') {
                array_push($permission, ['deleted' => 'Apagar']);
            } else if ($value['description'] == 'Recuperar') {
                array_push($permission, ['recover' => 'Recuperar']);
            }
        }

        return $permission;
    }

    public function show(int $id)
    {
        return $this->permissionService->show($id);
    }

    public function update(object $request)
    {
        $data = $request->except(['created_at']);

        return $this->permissionService->update($data);
    }

    public function destroy(int $id)
    {
        return $this->permissionService->destroy($id);
    }
}
