<?php

namespace App\Http\Controllers\Acl;
use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\RoleRequest;
use App\Services\Acl\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
       $this->roleService = $roleService;
    }

    public function search(Request $request)
    {
        Gate::authorize('search_role');

        return $this->roleService->search($request);
    }

    public function store(RoleRequest $request)
    {
        Gate::authorize('store_role');

        return $this->roleService->store($request);
    }

    public function show($uuid)
    {
        Gate::authorize('show_role');

        return $this->roleService->show($uuid);
    }


    public function update(RoleRequest $request)
    {
        Gate::authorize('update_role');

        return $this->roleService->update($request);
    }

    public function activate($uuid)
    {
        Gate::authorize('activate_role');

        return $this->roleService->activate($uuid);
    }

    public function inactivate($uuid)
    {
        Gate::authorize('inactivate_role');

        return $this->roleService->inactivate($uuid);
    }

    public function deleted($uuid)
    {
        Gate::authorize('deleted_role');

        return $this->roleService->deleted($uuid);
    }

    public function recover($uuid)
    {
        Gate::authorize('recover_role');

        return $this->roleService->recover($uuid);
    }

    public function destroy($uuid)
    {
        Gate::authorize('destroy_role');

        return $this->roleService->destroy($uuid);
    }

    public function rolePermissions($uuid)
    {
        if(Gate::none(['add_permission_role', 'remove_permission_role'])){
            abort(403);
        }

        return $this->roleService->rolePermissions($uuid);
    }

    public function attachPermissions(Request $request)
    {
        Gate::authorize('add_permission_role');

        return $this->roleService->attachPermissions($request);
    }

    public function detachPermissions(Request $request)
    {
        Gate::authorize('remove_permission_role');

        return $this->roleService->detachPermissions($request);
    }
}
