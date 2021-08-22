<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProcedureGroupRequest;
use App\Services\Admin\ProcedureGroupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProcedureGroupController extends Controller
{
    private $procedureGroupService;

    public function __construct(ProcedureGroupService $procedureGroupService)
    {
       $this->procedureGroupService = $procedureGroupService;
    }

    public function search(Request $request)
    {
        Gate::authorize('search_procedure_group');

        return $this->procedureGroupService->search($request);
    }

    public function store(ProcedureGroupRequest $request)
    {
        Gate::authorize('store_procedure_group');

        return $this->procedureGroupService->store($request);
    }

    public function show($uuid)
    {
        Gate::authorize('show_procedure_group');

        return $this->procedureGroupService->show($uuid);
    }


    public function update(ProcedureGroupRequest $request)
    {
        Gate::authorize('update_procedure_group');

        return $this->procedureGroupService->update($request);
    }


    public function deleted($uuid)
    {
        Gate::authorize('deleted_procedure_group');

        return $this->procedureGroupService->deleted($uuid);
    }

    public function recover($uuid)
    {
        Gate::authorize('recover_procedure_group');

        return $this->procedureGroupService->recover($uuid);
    }

    public function destroy($uuid)
    {
        Gate::authorize('destroy_procedure_group');

        return $this->procedureGroupService->destroy($uuid);
    }
}
