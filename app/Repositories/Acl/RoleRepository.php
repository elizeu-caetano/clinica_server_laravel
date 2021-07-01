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

            $roles = Role::where('active', $active)
            ->where('deleted', false)
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

            return ['status' => true, 'message' => 'O Papel foi cadastrado.', 'data' => new RoleResource($role)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Papel não foi cadastrado.', 'error' => $th->getMessage()];
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

            return ['status' => false, 'message' => 'O Papel não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function activate($uuid)
    {
        try {

            $role = DB::table('roles')->where('uuid', $uuid)->update(['active' => 1]);

            return ['status' => true, 'message' => 'O Papel foi ativado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Papel não foi ativado.', 'error' => $th->getMessage()];
        }
    }

    public function inactivate($uuid)
    {
        try {

            $role = DB::table('roles')->where('uuid', $uuid)->update(['active' => false]);

            return ['status' => true, 'message' => 'O Papel foi inativado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Papel não foi inativado.', 'error' => $th->getMessage()];
        }
    }

    public function deleted($uuid)
    {
        try {

            $role = DB::table('roles')->where('uuid', $uuid)->update(['deleted' => true]);

            return ['status' => true, 'message' => 'O Papel foi deletado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Papel não foi deletado.', 'error' => $th->getMessage()];
        }
    }

    public function recover($uuid)
    {
        try {

            $role = DB::table('roles')->where('uuid', $uuid)->update(['deleted' => false]);

            return ['status' => true, 'message' => 'O Papel foi recuperado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Papel não foi recuperado.', 'error' => $th->getMessage()];
        }
    }

    public function destroy($uuid)
    {
        try {
            $role = Role::where('uuid', $uuid)->first();

            $role->delete();

            return ['status'=>true, 'message'=> 'O Papel foi excluído.'];

        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'O Papel não foi excluído', 'error'=> $th->getMessage()];
        }
    }
}
