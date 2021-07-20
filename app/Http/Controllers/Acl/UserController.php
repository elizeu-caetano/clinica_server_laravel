<?php

namespace App\Http\Controllers\Acl;
use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\UserRequest;
use App\Http\Requests\Acl\UserUpdatePasswordRequest;
use App\Repositories\Acl\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    private $repository;

    public function __construct(UserRepositoryInterface $repository, Request $request)
    {
       $this->repository = $repository;
    }

    public function search(Request $request)
    {
        Gate::authorize('search_user');

        return $this->repository->search($request);
    }

    public function store(UserRequest $request)
    {
       Gate::authorize('store_user');

        return $this->repository->store($request);
    }

    public function storeAdmin(UserRequest $request)
    {
        Gate::authorize('store_user_admin');

        return $this->repository->storeAdmin($request);
    }

    public function show($uuid)
    {
       Gate::authorize('show_user');

        return $this->repository->show($uuid);
    }


    public function update(UserRequest $request)
    {
        Gate::authorize('update_user');

        return $this->repository->update($request);
    }

    public function activate($uuid)
    {
       Gate::authorize('activate_user');

        return $this->repository->activate($uuid);
    }

    public function inactivate($uuid)
    {
       Gate::authorize('inactivate_user');

        return $this->repository->inactivate($uuid);
    }

    public function deleted($uuid)
    {
       Gate::authorize('deleted_user');

        return $this->repository->deleted($uuid);
    }

    public function recover($uuid)
    {
       Gate::authorize('recover_user');

        return $this->repository->recover($uuid);
    }

    public function destroy($uuid)
    {
        Gate::authorize('destroy_user');

        return $this->repository->destroy($uuid);
    }

    public function profile()
    {
        return $this->repository->profile();
    }

    public function updatePassword(UserUpdatePasswordRequest $request)
    {
        return $this->repository->updatePassword($request);
    }
}
