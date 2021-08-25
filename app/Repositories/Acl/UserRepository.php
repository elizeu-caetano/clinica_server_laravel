<?php

namespace App\Repositories\Acl;

use App\Events\NewUser;
use App\Http\Resources\Acl\UserResource;
use App\Http\Resources\Admin\CompanyResource;
use App\Models\Acl\User;
use App\Repositories\Acl\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserRepositoryInterface
{
    private $repository;

    public function __construct(User $user)
    {
        $this->repository = $user;
    }

    public function search(array $data)
    {
        try {
            $users = $this->repository->with(['phones', 'contractor'])
                ->where('deleted', false)
                ->where('active', $data['active'])
                ->where(function ($query) {
                    if (Auth::user()->contractor_id != 1) {
                        $query->where('contractor_id', Auth::user()->contractor_id);
                    }
                })
                ->where(function ($query) use ($data) {
                    $query->where('name', 'LIKE', '%'.$data['search'].'%')
                        ->orWhere('email', 'LIKE', '%'.$data['search'].'%');
                })->get();

            return ['status' => true, 'data' => UserResource::collection($users)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function store(array $data, string $password)
    {
        try {
            $user = $this->repository->create($data);
            $user->phones()->create($data);

            $user->password = $password;
            event(new NewUser($user));

            return ['status' => true, 'message' => 'O Usuário foi cadastrado.', 'data' => new UserResource($user)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi cadastrado.', 'error' => $th->getMessage()];
        }
    }

    public function storeAdmin(array $data, string $password)
    {
        try {

            $user = $this->repository->create($data);
            $user->phones()->create($data);

            $user->password = $password;
            event(new NewUser($user));

            return ['status' => true, 'message' => 'O Usuário foi cadastrado.', 'data' => new UserResource($user)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi cadastrado.', 'error' => $th->getMessage()];
        }
    }

    public function show(string $uuid)
    {
        try {
            $user = $this->repository->where('uuid', $uuid)->first();

            return ['status' => true, 'data' => new UserResource($user)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update(array $data)
    {
        try {
            $user = $this->repository->where('uuid', $data['uuid'])->first();

            $user->update($data);

            $user->phones()->updateOrCreate(['type' => 'celular'], ['phone' => $data['cell']]);

            return ['status' => true, 'data' => new UserResource($user), 'message' => 'O Usuário foi editado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function activate(string $uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['active' => true]);

            $this->repository->where('uuid', $uuid)->first()->activateAudit('Usuários');

            return ['status' => true, 'message' => 'O Usuário foi ativado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi ativado.', 'error' => $th->getMessage()];
        }
    }

    public function inactivate(string $uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['active' => false]);

            $this->repository->where('uuid', $uuid)->first()->inactivateAudit('Usuários');

            return ['status' => true, 'message' => 'O Usuário foi inativado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi inativado.', 'error' => $th->getMessage()];
        }
    }

    public function deleted(string $uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['deleted' => true]);

            $this->repository->where('uuid', $uuid)->first()->deletedAudit('Usuários');

            return ['status' => true, 'message' => 'O Usuário foi deletado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function recover(string $uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['deleted' => false]);

            $this->repository->where('uuid', $uuid)->first()->recoverAudit('Usuários');

            return ['status' => true, 'message' => 'O Usuário foi Recuperado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Usuário não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function destroy(string $uuid)
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

    public function attachCompany(array $data)
    {
        try {
            $user = User::where('uuid', $data['user'])->first();
            $user->companies()->attach($data['company']);

            $companies = $user->companies()->get();

            return ['status' => true, 'message' => 'A Empresa foi adicionada.', 'data' => CompanyResource::collection($companies)];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'A Empresa não foi adicionada', 'error' => $th->getMessage()];
        }
    }

    public function detachCompany(array $data)
    {
        try {
            $user = User::where('uuid', $data['userUuid'])->first();
            $user->companies()->detach($data['companyId']);

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

    public function profileUpdate(array $data)
    {
        try {
            $user = Auth::user();

            $user->update($data);

            return ['status' => true, 'message' => 'O Usuário foi editado.'];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'O Usuário não foi editado', 'error' => $th->getMessage()];
        }
    }

    public function uploadPhotoProfile(object $user, string $path)
    {
        try {
            $user->update(['photo' => $path]);

            return ['status' => true, 'message' => 'A Foto foi adicionada.', 'data' => new UserResource($user)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Foto não foi adicionada.', 'error' => $th->getMessage()];
        }
    }

    public function updatePassword(string $password)
    {
        try {
            $user = Auth::user();

            $user->update(['password' => $password]);

            return ['status' => true, 'message' => 'A senha foi alterada.'];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'A senha não foi alterada.', 'error' => $th->getMessage()];
        }
    }
}
