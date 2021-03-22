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
            
            if ($request->plan) {
                $plan = Plan::find($request->plan);
                $plan->permissions()->attach($idPermissao);
            }
          
            return ['status' => true, 'message' => 'A Permissão foi cadastrada.', 'data' => new PermissionResource($permission)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Permissão não foi cadastrada.', 'error' => $th->getMessage()];
        }
    }

    private function createData($permissions)
    {
        $permission = [];
        foreach ($permissions as $value) {
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
            $permission = Permission::find($id);
          
            $permission->delete();
            
            return ['status'=>true, 'message'=> 'A Permissão foi excluída.'];

        } catch (\Throwable $th) {
            
            return ['status'=>false, 'message'=> 'A Permissão não foi excluído', 'error'=> $th->getMessage()];
        }
    }
}