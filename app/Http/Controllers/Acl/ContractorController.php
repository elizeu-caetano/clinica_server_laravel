<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\ContractorRequest;
use App\Http\Requests\ImageRequest;
use App\Services\Acl\ContractorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ContractorController extends Controller
{
    private $contractService;

    public function __construct(ContractorService $contractService)
    {
       $this->contractService = $contractService;
    }

    public function index()
    {
        Gate::authorize('store_contractor');

        return $this->contractService->index();
    }

    public function search(Request $request)
    {
        Gate::authorize('search_contractor');

        return $this->contractService->search($request);
    }

    public function store(ContractorRequest $request)
    {
        Gate::authorize('store_contractor');

        return $this->contractService->store($request);
    }

    public function show($uuid)
    {
        Gate::authorize('show_contractor');

        return $this->contractService->show($uuid);
    }


    public function update(ContractorRequest $request)
    {
       Gate::authorize('update_contractor');

        return $this->contractService->update($request);
    }

    public function activate($uuid)
    {
        Gate::authorize('activate_contractor');

        return $this->contractService->activate($uuid);
    }

    public function inactivate($uuid)
    {
        Gate::authorize('inactivate_contractor');

        return $this->contractService->inactivate($uuid);
    }

    public function destroy($uuid)
    {
        Gate::authorize('destroy_contractor');

        return $this->contractService->destroy($uuid);
    }

    public function uploadLogo(ImageRequest $request)
    {
        Gate::authorize('update_contractor');

        return $this->contractService->uploadLogo($request);
    }

    public function contractorPlans($uuid)
    {
        if(Gate::none(['add_plan_contractor', 'remove_plan_contractor'])){
            abort(403);
        }

        return $this->contractService->contractorPlans($uuid);
    }

    public function attachPlans(Request $request)
    {
        Gate::authorize('add_plan_contractor');

        return $this->contractService->attachPlans($request);
    }

    public function detachPlans(Request $request)
    {
        Gate::authorize('remove_plan_contractor');

        return $this->contractService->detachPlans($request);
    }
}
