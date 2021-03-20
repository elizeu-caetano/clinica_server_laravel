<?php

namespace App\Repositories\Acl;

use App\Http\Resources\Acl\UserResource;
use App\Models\Acl\User;
use App\Models\Acl\PhonesUser;
use App\Repositories\Acl\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserRepositoryInterface {

    public function search($request)
    {
        try {
            
            $active = !$request->active ? false : true;
            $search = $request->search;

            $users = User::where('deleted', false)
            ->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");                
            })->get();

            return ['status' => true, 'data' => UserResource::collection($users)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function store($request)
    {
        try {
            $data = $request->all();
            $senha = rand(111111, 999999);
            $data['logo'] = $senha;
            $data['password'] = Hash::make($senha);
            $data['uuid'] = Str::uuid();
            $data['contractor_id'] = Auth::user()->contractor_id;
            $data['phone'] = $request->cell;
            $data['type'] = 'Celular';

            $user = User::create($data);

            $user->phones()->create($data);

            return ['status' => true, 'message' => 'O Usuário foi cadastrado.', 'data' => new UserResource($user)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi cadastrado.', 'error' => $th->getMessage()];
        }
    }

    public function show($uuid)
    {
        try {

            $user = User::where('uuid', $uuid)->first();

            return ['status' => true, 'data' => new UserResource($user)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update($request)
    {
        try {      
            $data = $request->except(['deleted', 'created_at']);
           
            $user = User::where('uuid', $request->uuid)->first();
            $phone = $user->phones->first();
            
           $user->update($data);
            if ($phone) {
                $phone->update(['phone' => $data['cell']]);
            } else {
                $user->phones()->create(['phone' => $data['cell'], 'type' => 'celular']);
            }
                 
           
            return ['status' => true, 'data' => new UserResource($user) , 'upadate' => true, 'message' => 'O Usuário foi editado.'];
           
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function activate($uuid)
    {
        try {
            
            $update = DB::table('users')->where('uuid', $uuid)->update(['active' => 1]);

            if ($update) {
                return ['status' => true, 'upadate' => true, 'message' => 'O Usuário foi ativado.'];
            } else {
                return ['status' => true, 'upadate' => false, 'message' => 'Sem alterações para ativar.'];
            }


        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function inactivate($uuid)
    {
        try {

            $update = DB::table('users')->where('uuid', $uuid)->update(['active' => false]);

            if ($update) {
                return ['status' => true, 'upadate' => true, 'message' => 'O Usuário foi inativado.'];
            } else {
                return ['status' => true, 'upadate' => false, 'message' => 'Sem alterações para inativar.'];
            }


        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function deleted($uuid)
    {
        try {
            
            $update = DB::table('users')->where('uuid', $uuid)->update(['deleted' => true]);

            if ($update) {
                return ['status' => true, 'upadate' => true, 'message' => 'O Usuário foi deletado.'];
            } else {
                return ['status' => true, 'upadate' => false, 'message' => 'Sem alterações para deletar.'];
            }


        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function recover($uuid)
    {
        try {

            $update = DB::table('users')->where('uuid', $uuid)->update(['deleted' => false]);

            if ($update) {
                return ['status' => true, 'upadate' => true, 'message' => 'O Usuário foi Recuperado.'];
            } else {
                return ['status' => true, 'upadate' => false, 'message' => 'Sem alterações para recuperar.'];
            }


        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function destroy($uuid)
    {
        try {
            $destroy = User::where('uuid', $uuid)->first();
          
            $destroy->delete();
            
            return ['status'=>true, 'message'=> 'O Usuário foi excluído.'];

        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'O Usuário não foi excluído', 'error'=> $th->getMessage()];
        }
    }
}