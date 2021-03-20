<?php

namespace App\Repositories\Acl;

use App\Http\Resources\Acl\PermissionResource;
use App\Models\Acl\Permission;
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

            if (!$request->lote) {
                
                $data = $request->all();
                $permission = Permission::create($data);

            } else {
           
                $permissions = $this->createData($request);

                for ($i=0; $i < count($permissions); $i++) { 
                
                    foreach ($permissions[$i] as $key => $value) {
                        $data['name'] = $value . ' ' . $request->name;
                        $data['permission'] = $key . '_' . $request->model;
                    }
                    $permission = Permission::create($data);
                } 
            }       

            return ['status' => true, 'message' => 'A Permissão foi cadastrada.', 'data' => new PermissionResource($permission)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Permissão não foi cadastrada.', 'error' => $th->getMessage()];
        }
    }

    private function createData($request)
    {
        $permission = [];
        foreach ($request->permission as $value) {
            if ($value == 'search') {
                array_push($permission, ['search' => 'Listar']);
            } else if ($value == 'store') {
                array_push($permission, ['store' => 'Cadastrar']);
            } else if ($value == 'show') {
                array_push($permission, ['show' => 'Visualizar']);
            } else if ($value == 'update') {
                array_push($permission, ['update' => 'Editar']);
            } else if ($value == 'activate') {
                array_push($permission, ['activate' => 'Ativar']);
            } else if ($value == 'inactivate') {
                array_push($permission, ['inactivate' => 'Inativar']);
            } else if ($value == 'destroy') {
                array_push($permission, ['destroy' => 'Excluir']);
            } else if ($value == 'deleted') {
                array_push($permission, ['deleted' => 'Apagar']);
            } else if ($value == 'recover') {
                array_push($permission, ['recover' => 'Recuperar']);
            }     
        }

        return $permission;
    }

    public function show($uuid)
    {
        try {

            $permission = Permission::where('uuid', $uuid)->first();

            return ['status' => true, 'data' => new PermissionResource($permission)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update($request)
    {
        try {      

            $data = $request->all();
           
            $update = DB::table('permissions')->where('uuid', $request->uuid)->update($data);

            if ($update) {
                return ['status' => true, 'upadate' => true, 'message' => 'A Permissão foi editada.'];
            } else {
                return ['status' => true, 'upadate' => false, 'message' => 'Sem alterações para editar.'];
            }

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Permissão não foi editada.', 'error' => $th->getMessage()];
        }
    }
    
    public function destroy($uuid)
    {
        try {
            $destroy = Permission::where('uuid', $uuid)->first();
          
            $destroy->delete();
            
            return ['status'=>true, 'message'=> 'A Permissão foi excluída.'];

        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'A Permissão não foi excluído', 'error'=> $th->getMessage()];
        }
    }
}