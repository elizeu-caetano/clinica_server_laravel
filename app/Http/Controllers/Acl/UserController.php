<?php

namespace App\Http\Controllers\Acl;
use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\UserRequest;
use App\Http\Requests\Acl\UserUpdatePasswordRequest;
use App\Http\Requests\ImageRequest;
use App\Repositories\Acl\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
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

    public function attachCompany(Request $request)
    {

       Gate::authorize('add_company_user');

        return $this->repository->attachCompany($request);
    }

    public function detachCompany(Request $request)
    {

       Gate::authorize('remove_company_user');

        return $this->repository->detachCompany($request);
    }

    public function profile()
    {
        Gate::authorize('update_profile_user');
        return $this->repository->profile();
    }

    public function profileUpdate(UserRequest $request)
    {
        Gate::authorize('update_profile_user');
        return $this->repository->profileUpdate($request);
    }

    public function updatePassword(UserUpdatePasswordRequest $request)
    {
        Gate::authorize('update_password_profile_user');
        return $this->repository->updatePassword($request);
    }

    public function uploadPhotoProfile(ImageRequest $request)
    {
        Gate::authorize('update_profile_user');
        return $this->repository->uploadPhotoProfile($request);
    }
}
