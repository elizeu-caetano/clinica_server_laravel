<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUpdateProcedureRequest;
use App\Services\Admin\ProcedureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProcedureController extends Controller
{
    private $procedureService;

    public function __construct(ProcedureService $procedureService)
    {
       $this->procedureService = $procedureService;
    }

    public function search(Request $request)
    {
        Gate::authorize('search_procedure');

        return $this->procedureService->search($request);
    }

    public function store(StoreUpdateProcedureRequest $request)
    {
        Gate::authorize('store_procedure');

        return $this->procedureService->store($request);
    }

    public function show($uuid)
    {
        Gate::authorize('show_procedure');

        return $this->procedureService->show($uuid);
    }


    public function update(StoreUpdateProcedureRequest $request)
    {
        Gate::authorize('update_procedure');

        return $this->procedureService->update($request);
    }

    public function activate($uuid)
    {
        Gate::authorize('activate_procedure');

        return $this->procedureService->activate($uuid);
    }

    public function inactivate($uuid)
    {
        Gate::authorize('inactivate_procedure');

        return $this->procedureService->inactivate($uuid);
    }


    public function deleted($uuid)
    {
        Gate::authorize('deleted_procedure');

        return $this->procedureService->deleted($uuid);
    }

    public function recover($uuid)
    {
        Gate::authorize('recover_procedure');

        return $this->procedureService->recover($uuid);
    }

    public function destroy($uuid)
    {
        Gate::authorize('destroy_procedure');

        return $this->procedureService->destroy($uuid);
    }
}
