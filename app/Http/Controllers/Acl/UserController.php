<?php

namespace App\Http\Controllers\Acl;
use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\UserRequest;
use App\Http\Requests\Acl\UserUpdatePasswordRequest;
use App\Http\Requests\ImageRequest;
use App\Services\Acl\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
       $this->userService = $userService;
    }

    public function search(Request $request)
    {
        Gate::authorize('search_user');

        return $this->userService->search($request);
    }

    public function store(UserRequest $request)
    {
       Gate::authorize('store_user');

        return $this->userService->store($request);
    }

    public function storeAdmin(UserRequest $request)
    {
        Gate::authorize('store_user_admin');

        return $this->userService->storeAdmin($request);
    }

    public function show($uuid)
    {
       Gate::authorize('show_user');

        return $this->userService->show($uuid);
    }


    public function update(UserRequest $request)
    {
        Gate::authorize('update_user');

        return $this->userService->update($request);
    }

    public function activate($uuid)
    {
       Gate::authorize('activate_user');

        return $this->userService->activate($uuid);
    }

    public function inactivate($uuid)
    {
       Gate::authorize('inactivate_user');

        return $this->userService->inactivate($uuid);
    }

    public function deleted($uuid)
    {
       Gate::authorize('deleted_user');

        return $this->userService->deleted($uuid);
    }

    public function recover($uuid)
    {
       Gate::authorize('recover_user');

        return $this->userService->recover($uuid);
    }

    public function destroy($uuid)
    {
        Gate::authorize('destroy_user');

        return $this->userService->destroy($uuid);
    }

    public function attachCompany(Request $request)
    {

       Gate::authorize('add_company_user');

        return $this->userService->attachCompany($request);
    }

    public function detachCompany(Request $request)
    {

       Gate::authorize('remove_company_user');

        return $this->userService->detachCompany($request);
    }

    public function profile()
    {
        Gate::authorize('update_profile_user');
        return $this->userService->profile();
    }

    public function profileUpdate(UserRequest $request)
    {
        Gate::authorize('update_profile_user');
        return $this->userService->profileUpdate($request);
    }

    public function updatePassword(UserUpdatePasswordRequest $request)
    {
        Gate::authorize('update_password_profile_user');
        return $this->userService->updatePassword($request);
    }

    public function uploadPhotoProfile(ImageRequest $request)
    {
        Gate::authorize('update_profile_user');
        return $this->userService->uploadPhotoProfile($request);
    }
}
