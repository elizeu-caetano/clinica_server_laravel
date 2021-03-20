<?php

namespace App\Repositories\Acl;

use App\Http\Resources\Acl\RoleResource;
use App\Models\Acl\Role;
use App\Repositories\Acl\Contracts\RoleRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleRepository implements RoleRepositoryInterface {

    public function search($request)
    {
        try {
            
            $active = !$request->active ? false : true;
            $search = $request->search;

            $roles = Role::where('deleted', false)
            ->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%");                
            })->get();

            return ['status' => true, 'data' => RoleResource::collection($roles)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function store($request)
    {
        try {

            $data = $request->all();
            $data['uuid'] = Str::uuid();
            $data['contractor_id'] = Auth::user()->contractor_id;

            $role = Role::create($data);

            return ['status' => true, 'message' => 'A Função foi cadastrada.', 'data' => new RoleResource($role)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Papel foi cadastrado.', 'error' => $th->getMessage()];
        }
    }

    public function show($uuid)
    {
        try {

            $role = Role::where('uuid', $uuid)->first();

            return ['status' => true, 'data' => new RoleResource($role)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update($request)
    {
        try {      

            $data = $request->except(['admin', 'active', 'deleted', 'created_at']);
           
            $role = Role::where('uuid', $request->uuid)->first();
            $role->update($data);
           
            return ['status' => true, 'data' => new RoleResource($role), 'message' => 'O Papel foi editado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Função não foi editada.', 'error' => $th->getMessage()];
        }
    }

    public function activate($uuid)
    {
        try {
            
            $update = DB::table('roles')->where('uuid', $uuid)->update(['active' => 1]);

            if ($update) {
                return ['status' => true, 'upadate' => true, 'message' => 'A Função foi ativada.'];
            } else {
                return ['status' => true, 'upadate' => false, 'message' => 'Sem alterações para ativar.'];
            }


        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Função não foi editada.', 'error' => $th->getMessage()];
        }
    }

    public function inactivate($uuid)
    {
        try {

            $update = DB::table('roles')->where('uuid', $uuid)->update(['active' => false]);

            if ($update) {
                return ['status' => true, 'upadate' => true, 'message' => 'A Função foi inativada.'];
            } else {
                return ['status' => true, 'upadate' => false, 'message' => 'Sem alterações para inativar.'];
            }


        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Função não foi editada.', 'error' => $th->getMessage()];
        }
    }

    public function deleted($uuid)
    {
        try {
            
            $update = DB::table('roles')->where('uuid', $uuid)->update(['deleted' => true]);

            if ($update) {
                return ['status' => true, 'upadate' => true, 'message' => 'A Função foi deletada.'];
            } else {
                return ['status' => true, 'upadate' => false, 'message' => 'Sem alterações para deletar.'];
            }


        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Função não foi editada.', 'error' => $th->getMessage()];
        }
    }

    public function recover($uuid)
    {
        try {

            $update = DB::table('roles')->where('uuid', $uuid)->update(['deleted' => false]);

            if ($update) {
                return ['status' => true, 'upadate' => true, 'message' => 'A Função foi Recuperada.'];
            } else {
                return ['status' => true, 'upadate' => false, 'message' => 'Sem alterações para recuperar.'];
            }


        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Função não foi editada.', 'error' => $th->getMessage()];
        }
    }

    public function destroy($uuid)
    {
        try {
            $destroy = Role::where('uuid', $uuid)->first();
          
            $destroy->delete();
            
            return ['status'=>true, 'message'=> 'A Função foi excluída.'];

        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'A Função não foi excluído', 'error'=> $th->getMessage()];
        }
    }
}