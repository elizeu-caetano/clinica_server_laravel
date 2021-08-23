<?php

namespace App\Services\Acl;

use App\Repositories\Acl\Contracts\RoleRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\support\Str;

class RoleService
{
    protected $roleService;

    public function __construct(RoleRepositoryInterface $roleService)
    {
        $this->roleService = $roleService;
    }

    public function search(object $request)
    {
        $data = $request->all();
        $data['active'] = !$request->active ? false : true;

        return $this->roleService->search($data);
    }

    public function store(object $request)
    {
        $data = $request->all();
        $data['uuid'] = Str::uuid();
        $data['contractor_id'] = $request->contractor_id ? $request->contractor_id : Auth::user()->contractor_id;

        return $this->roleService->store($data);
    }

    public function show(string $uuid)
    {
        return $this->roleService->show($uuid);
    }

    public function update(object $request)
    {
        $data = $request->except(['admin', 'active', 'deleted', 'created_at']);

        return $this->roleService->update($data);
    }

    public function activate(string $uuid)
    {
        return $this->roleService->activate($uuid);
    }

    public function inactivate(string $uuid)
    {
        return $this->roleService->inactivate($uuid);
    }

    public function deleted(string $uuid)
    {
        return $this->roleService->deleted($uuid);
    }

    public function recover(string $uuid)
    {
        return $this->roleService->recover($uuid);
    }

    public function destroy(string $uuid)
    {
        return $this->roleService->destroy($uuid);
    }

    public function rolePermissions(string $uuid)
    {
        return $this->roleService->rolePermissions($uuid);
    }

    public function attachPermissions(object $request)
    {
        $data = $request->all();
        return $this->roleService->attachPermissions($data);
    }

    public function detachPermissions(object $request)
    {
        $data = $request->all();
        return $this->roleService->detachPermissions($data);
    }
}
