<?php

namespace App\Repositories\Acl;

use App\Http\Resources\Acl\PermissionResource;
use App\Models\Acl\Permission;
use App\Models\Acl\Plan;
use App\Repositories\Acl\Contracts\PermissionRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionRepository implements PermissionRepositoryInterface {

    public function search($request)
    {
        try {

            $search = $request->search;

            $permissions = Permission::where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('permission', 'LIKE', "%{$search}%");
            })->get();

            return ['status' => true, 'data' => PermissionResource::collection($permissions)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function store($request)
    {

        try {

            if ($request->lote) {
                $permission = $this->storeBatch($request);
            } else {
                $data['name'] = $request->name;
                $data['permission'] = $request->permission;

                $permission = Permission::create($data);
            }

            return ['status' => true, 'message' => 'A Permissão foi cadastrada.', 'data' => new PermissionResource($permission)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Permissão não foi cadastrada.', 'error' => $th->getMessage()];
        }
    }

    private function storeBatch($request)
    {
        $idPermissao = [];

        $permissions = $this->createData($request->listPermission);

        for ($i=0; $i < count($permissions); $i++) {

            foreach ($permissions[$i] as $key => $value) {
                $data['name'] = $value . ' ' . $request->name;
                $data['permission'] = $key . '_' . $request->permission;
            }
            $permission = Permission::create($data);
            array_push($idPermissao, $permission->id);
        }

        return $permission;
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

    public function show($id)
    {
        try {

            $permission = Permission::find($id);

            return ['status' => true, 'data' => new PermissionResource($permission)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update($request)
    {
        try {

            $data = $request->except(['created_at']);

            $permission = Permission::find($request->id);
            $permission->update($data);

            return ['status' => true, 'message' => 'A Permissão foi editada.', 'data' => new PermissionResource($permission)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Permissão não foi editada.', 'error' => $th->getMessage()];
        }
    }

    public function destroy($id)
    {
        try {
            Permission::find($id)->delete();

            return ['status'=> true, 'message'=> 'A Permissão foi excluída.'];

        } catch (\Throwable $th) {

            return ['status'=> false, 'message'=> 'A Permissão não foi excluído', 'error'=> $th->getMessage()];
        }
    }
}
