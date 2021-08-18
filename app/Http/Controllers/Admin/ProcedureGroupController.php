<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProcedureGroupRequest;
use App\Repositories\Admin\Contracts\ProcedureGroupRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProcedureGroupController extends Controller
{
    private $repository;

    public function __construct(ProcedureGroupRepositoryInterface $repository)
    {
       $this->repository = $repository;
    }

    public function search(Request $request)
    {
        Gate::authorize('search_procedure_group');

        return $this->repository->search($request);
    }

    public function store(ProcedureGroupRequest $request)
    {
        Gate::authorize('store_procedure_group');

        return $this->repository->store($request);
    }

    public function show($uuid)
    {
        Gate::authorize('show_procedure_group');

        return $this->repository->show($uuid);
    }


    public function update(ProcedureGroupRequest $request)
    {
        Gate::authorize('update_procedure_group');

        return $this->repository->update($request);
    }


    public function deleted($uuid)
    {
        Gate::authorize('deleted_procedure_group');

        return $this->repository->deleted($uuid);
    }

    public function recover($uuid)
    {
        Gate::authorize('recover_procedure_group');

        return $this->repository->recover($uuid);
    }

    public function destroy($uuid)
    {
        Gate::authorize('destroy_procedure_group');

        return $this->repository->destroy($uuid);
    }
}
