<?php

namespace App\Http\Controllers\JobMed;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobMed\StoreUpdateCompanyOccupationRequest;
use App\Services\JobMed\CompanyOccupationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CompanyOccupationController extends Controller
{
    private $service;

    public function __construct(CompanyOccupationService $service)
    {
       $this->service = $service;
    }

    public function index($id)
    {
        Gate::authorize('search_company_occupation');

        return $this->service->index($id);
    }

    public function search(Request $request)
    {
        Gate::authorize('search_company_occupation');

        return $this->service->search($request);
    }

    public function store(StoreUpdateCompanyOccupationRequest $request)
    {
        Gate::authorize('store_company_occupation');

        return $this->service->store($request);
    }

    public function show($id)
    {
        Gate::authorize('show_company_occupation');

        return $this->service->show($id);
    }


    public function update(Request $request)
    {
        Gate::authorize('update_company_occupation');

        return $this->service->update($request);
    }

    public function destroy($id)
    {
        Gate::authorize('destroy_company_occupation');

        return $this->service->destroy($id);
    }
}
