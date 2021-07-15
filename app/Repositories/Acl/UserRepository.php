<?php

namespace App\Repositories\Acl;

use App\Events\NewUser;
use App\Http\Resources\Acl\UserResource;
use App\Mail\AuthMail;
use App\Mail\UserRegisteredMail;
use App\Models\Acl\User;
use App\Models\Acl\PhonesUser;
use App\Repositories\Acl\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\Bridge\UserRepository as BridgeUserRepository;

class UserRepository implements UserRepositoryInterface {

    public function search($request)
    {
        try {

            $active = !$request->active ? false : true;
            $search = $request->search;

            $users = User::where('deleted', false)
            ->where('active', $active)
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
            $data['password'] = Hash::make($senha);
            $data['uuid'] = Str::uuid();
            $data['contractor_id'] = Auth::user()->contractor_id;
            $data['phone'] = $request->cell;
            $data['type'] = 'Celular';
            $data['token'] = Str::random(40);

            $user = User::create($data);
            $user->phones()->create($data);

            $user->password = $senha;
            event(new NewUser($user));

            return ['status' => true, 'message' => 'O Usuário foi cadastrado.', 'data' => new UserResource($user)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi cadastrado.', 'error' => $th->getMessage()];
        }
    }

    public function storeAdmin($request)
    {
        try {
            $data = $request->all();
            $senha = rand(111111, 999999);
            $data['password'] = Hash::make($senha);
            $data['uuid'] = Str::uuid();
            $data['phone'] = $request->cell;
            $data['type'] = 'Celular';
            $data['token'] = Str::random(40);

            $user = User::create($data);
            $user->phones()->create($data);

            $user->password = $senha;
            event(new NewUser($user));

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


            return ['status' => true, 'data' => new UserResource($user) , 'message' => 'O Usuário foi editado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function activate($uuid)
    {
        try {

            $user = DB::table('users')->where('uuid', $uuid)->update(['active' => 1]);

            return ['status' => true, 'message' => 'O Usuário foi ativado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi ativado.', 'error' => $th->getMessage()];
        }
    }

    public function inactivate($uuid)
    {
        try {
            $user = DB::table('users')->where('uuid', $uuid)->update(['active' => false]);

            return ['status' => true, 'message' => 'O Usuário foi inativado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi inativado.', 'error' => $th->getMessage()];
        }
    }

    public function deleted($uuid)
    {
        try {

            $user = DB::table('users')->where('uuid', $uuid)->update(['deleted' => true]);

            return ['status' => true, 'message' => 'O Usuário foi deletado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function recover($uuid)
    {
        try {

            $user = DB::table('users')->where('uuid', $uuid)->update(['deleted' => false]);

            return ['status' => true, 'message' => 'O Usuário foi Recuperado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function destroy($uuid)
    {
        try {
            $user = User::where('uuid', $uuid)->first();

            $user->phones()->delete();
            $user->delete();

            return ['status'=>true, 'message'=> 'O Usuário foi excluído.'];

        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'O Usuário não foi excluído', 'error'=> $th->getMessage()];
        }
    }

}
