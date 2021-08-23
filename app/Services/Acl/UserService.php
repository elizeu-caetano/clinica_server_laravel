<?php

namespace App\Services\Acl;

use App\Repositories\Acl\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\support\Str;
use Illuminate\Support\Facades\Storage;
use Image;

class UserService
{
    protected $userService;

    public function __construct(UserRepositoryInterface $userService)
    {
        $this->userService = $userService;
    }

    public function search(object $request)
    {
        $data = $request->all();
        $data['active'] = !$request->active ? false : true;

        return $this->userService->search($data);
    }

    public function store(object $request)
    {
        $data = $request->all();
        $password = rand(111111, 999999);
        $data['password'] = Hash::make($password);
        $data['uuid'] = Str::uuid();
        $data['contractor_id'] = Auth::user()->contractor_id;
        $data['phone'] = $request->cell;
        $data['type'] = 'Celular';
        $data['token'] = Str::random(40);

        return $this->userService->store($data, $password);
    }

    public function storeAdmin(object $request)
    {
        $data = $request->all();
        $password = rand(111111, 999999);
        $data['password'] = Hash::make($password);
        $data['uuid'] = Str::uuid();
        $data['phone'] = $request->cell;
        $data['type'] = 'Celular';
        $data['token'] = Str::random(40);

        return $this->userService->storeAdmin($data, $password);
    }

    public function show(string $uuid)
    {
        return $this->userService->show($uuid);
    }

    public function update(object $request)
    {
        $data = $request->except(['deleted', 'created_at', 'photo']);

        return $this->userService->update($data);
    }

    public function activate(string $uuid)
    {
        return $this->userService->activate($uuid);
    }

    public function inactivate(string $uuid)
    {
        return $this->userService->inactivate($uuid);
    }

    public function deleted(string $uuid)
    {
        return $this->userService->deleted($uuid);
    }

    public function recover(string $uuid)
    {
        return $this->userService->recover($uuid);
    }

    public function destroy(string $uuid)
    {
        return $this->userService->destroy($uuid);
    }

    public function attachCompany(object $request)
    {
        $data = $request->all();
        return $this->userService->attachCompany($data);
    }

    public function detachCompany(object $request)
    {
        $data = $request->all();
        return $this->userService->detachCompany($data);
    }

    public function profile()
    {
        return $this->userService->profile();
    }

    public function profileUpdate(object $request)
    {
        $data = $request->all();
        return $this->userService->profileUpdate($data);
    }

    public function uploadPhotoProfile(object $request)
    {
        $user = Auth::user();

        $extension = request()->file('image')->getClientOriginalExtension();
        $image = Image::make(request()->file('image'))->resize(500, 500)->encode($extension);
        $path = 'users/' . Str::random(40) . '.' . $extension;
        Storage::disk('s3')->put($path, (string)$image, 'public');

        if ($user->photo != '') {
            Storage::disk('s3')->delete($user->photo);
        }

        return $this->userService->uploadPhotoProfile($user, $path);
    }

    public function updatePassword(object $request)
    {
        $password = Hash::make($request->password);
        return $this->userService->updatePassword($password);
    }

}
