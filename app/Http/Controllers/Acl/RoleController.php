<?php

namespace App\Http\Controllers\Acl;
use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\RoleRequest;
use App\Repositories\Acl\Contracts\RoleRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    private $repository;

    public function __construct(RoleRepositoryInterface $repository, Request $request)
    {
       $this->repository = $repository;
    }

    public function search(Request $request)
    {
        Gate::authorize('search_role');

        return $this->repository->search($request);
    }

    public function store(RoleRequest $request)
    {
        Gate::authorize('store_role');

        return $this->repository->store($request);
    }

    public function show($uuid)
    {
        Gate::authorize('show_role');

        return $this->repository->show($uuid);
    }


    public function update(RoleRequest $request)
    {
        Gate::authorize('update_role');

        return $this->repository->update($request);
    }

    public function activate($uuid)
    {
        Gate::authorize('activate_role');

        return $this->repository->activate($uuid);
    }

    public function inactivate($uuid)
    {
        Gate::authorize('inactivate_role');

        return $this->repository->inactivate($uuid);
    }

    public function deleted($uuid)
    {
        Gate::authorize('deleted_role');

        return $this->repository->deleted($uuid);
    }

    public function recover($uuid)
    {
        Gate::authorize('recover_role');

        return $this->repository->recover($uuid);
    }

    public function destroy($uuid)
    {
        Gate::authorize('destroy_role');

        return $this->repository->destroy($uuid);
    }

    public function rolePermissions($uuid)
    {
        if(Gate::none(['add_permission_role', 'remove_permission_role'])){
            abort(403);
        }

        return $this->repository->rolePermissions($uuid);
    }

    public function attachPermissions(Request $request)
    {
        Gate::authorize('add_permission_role');

        return $this->repository->attachPermissions($request);
    }

    public function detachPermissions(Request $request)
    {
        Gate::authorize('remove_permission_role');

        return $this->repository->detachPermissions($request);
    }
}
