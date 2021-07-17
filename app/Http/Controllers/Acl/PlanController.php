<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\PlanRequest;
use App\Repositories\Acl\Contracts\PlanRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PlanController extends Controller
{
    private $repository;

    public function __construct(PlanRepositoryInterface $repository, Request $request)
    {
       $this->repository = $repository;
    }

    public function search(Request $request)
    {
       Gate::authorize('search_plan');

        return $this->repository->search($request);
    }

    public function store(PlanRequest $request)
    {
       Gate::authorize('store_plan');

        return $this->repository->store($request);
    }

    public function show($id)
    {
       Gate::authorize('show_plan');

        return $this->repository->show($id);
    }


    public function update(PlanRequest $request)
    {
       Gate::authorize('update_plan');

        return $this->repository->update($request);
    }

    public function activate($id)
    {
       Gate::authorize('activate_plan');

        return $this->repository->activate($id);
    }

    public function inactivate($id)
    {
       Gate::authorize('inactivate_plan');

        return $this->repository->inactivate($id);
    }

    public function destroy($id)
    {
       Gate::authorize('destroy_plan');

        return $this->repository->destroy($id);
    }

    public function planPermissions($uuid)
    {
        if(Gate::none(['add_permission_plan', 'remove_permission_plan'])){
            abort(403);
        }

        return $this->repository->planPermissions($uuid);
    }

    public function attachPermissions(Request $request)
    {
        Gate::authorize('add_permission_plan');

        return $this->repository->attachPermissions($request);
    }

    public function detachPermissions(Request $request)
    {
        Gate::authorize('remove_permission_plan');

        return $this->repository->detachPermissions($request);
    }
}
