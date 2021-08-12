<?php

namespace App\Repositories\Acl;

use App\Events\NewUser;
use App\Http\Resources\Acl\UserResource;
use App\Http\Resources\Admin\CompanyResource;
use App\Models\Acl\User;
use App\Repositories\Acl\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Image;

class UserRepository implements UserRepositoryInterface
{
    private $repository;

    public function __construct(User $user)
    {
        $this->repository = $user;
    }

    public function search($request)
    {
        try {

            $active = !$request->active ? false : true;
            $search = $request->search;

            $users = $this->repository->where('deleted', false)
                ->where('active', $active)
                ->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                })->get();


            if (Auth::user()->contractor_id != 1) {
                $users = $users->where('contractor_id', Auth::user()->contractor_id);
            }

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

            $user = $this->repository->create($data);
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

            $user = $this->repository->create($data);
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

            $user = $this->repository->where('uuid', $uuid)->first();

            return ['status' => true, 'data' => new UserResource($user)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update($request)
    {
        try {
            $data = $request->except(['deleted', 'created_at']);

            $user = $this->repository->where('uuid', $request->uuid)->first();
            $phone = $user->phones->where('type', 'celular')->first();

            $user->update($data);

            if ($phone) {
                $phone->update([
                    'phone' => $data['cell']
                ]);
            } else {
                $user->phones()->create([
                    'phone' => $data['cell'],
                    'type' => 'celular'
                ]);
            }

            return ['status' => true, 'data' => new UserResource($user), 'message' => 'O Usuário foi editado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function activate($uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['active' => true]);

            $this->repository->where('uuid', $uuid)->first()->activateAudit('Usuários');

            return ['status' => true, 'message' => 'O Usuário foi ativado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi ativado.', 'error' => $th->getMessage()];
        }
    }

    public function inactivate($uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['active' => false]);

            $this->repository->where('uuid', $uuid)->first()->inactivateAudit('Usuários');

            return ['status' => true, 'message' => 'O Usuário foi inativado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi inativado.', 'error' => $th->getMessage()];
        }
    }

    public function deleted($uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['deleted' => true]);

            $this->repository->where('uuid', $uuid)->first()->deletedAudit('Usuários');

            return ['status' => true, 'message' => 'O Usuário foi deletado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function recover($uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['deleted' => false]);

            $this->repository->where('uuid', $uuid)->first()->recoverAudit('Usuários');

            return ['status' => true, 'message' => 'O Usuário foi Recuperado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function destroy($uuid)
    {
        try {
            $user = $this->repository->where('uuid', $uuid)->first();

            $user->phones()->delete();
            $user->roles()->detach();

            $user->delete();

            return ['status' => true, 'message' => 'O Usuário foi excluído.'];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'O Usuário não foi excluído', 'error' => $th->getMessage()];
        }
    }

    public function attachCompany($request)
    {
        try {
            $user = User::where('uuid', $request->user)->first();
            $user->companies()->attach($request->company);

            $companies = $user->companies()->get();

            return ['status' => true, 'message' => 'A Empresa foi adicionada.', 'data' => CompanyResource::collection($companies)];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'A Empresa não foi adicionada', 'error' => $th->getMessage()];
        }
    }

    public function detachCompany($request)
    {
        try {
            $user = User::where('uuid', $request->userUuid)->first();
            $user->companies()->detach($request->companyId);

            $companies = $user->companies()->get();

            return ['status' => true, 'message' => 'A Empresa foi adicionada.', 'data' => CompanyResource::collection($companies)];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'A Empresa não foi adicionada', 'error' => $th->getMessage()];
        }
    }

    public function profile()
    {
        try {
            $user = Auth::user();

            return ['status' => true, 'data' => new UserResource($user)];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'O Usuário não foi excluído', 'error' => $th->getMessage()];
        }
    }

    public function profileUpdate($request)
    {
        try {
            $user = Auth::user();

            $user->update($request->all());

            return ['status' => true, 'message' => 'O Usuário foi editado.'];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'O Usuário não foi editado', 'error' => $th->getMessage()];
        }
    }

    public function updatePassword($request)
    {
        try {
            $user = Auth::user();

            $senha =  Hash::make($request->password);

            $user->update(['password' => $senha]);

            return ['status' => true, 'message' => 'A senha foi alterada.'];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'A senha não foi alterada.', 'error' => $th->getMessage()];
        }
    }

    public function uploadPhotoProfile($request)
    {
        try {
            $user = Auth::user();

            $extension = request()->file('image')->getClientOriginalExtension();
            $image = Image::make(request()->file('image'))->resize(500, 500)->encode($extension);
            $path = 'users/' . Str::random(40) . '.' . $extension;
            Storage::disk('s3')->put($path, (string)$image, 'public');

            if ($user->photo != '') {
                Storage::disk('s3')->delete($user->photo);
            }

            $user->update(['photo' => $path]);

            return ['status' => true, 'message' => 'A Foto foi adicionada.', 'data' => new UserResource($user)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Foto não foi adicionada.', 'error' => $th->getMessage()];
        }
    }
}
