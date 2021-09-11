<?php

namespace App\Http\Controllers\JobMed;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobMed\StoreUpdateOccupationRequest;
use App\Services\JobMed\OccupationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OccupationController extends Controller
{
    private $service;

    public function __construct(OccupationService $service)
    {
       $this->service = $service;
    }

    public function search(Request $request)
    {
        Gate::authorize('search_occupation');

        return $this->service->search($request);
    }

    public function store(StoreUpdateOccupationRequest $request)
    {
        Gate::authorize('store_occupation');

        return $this->service->store($request);
    }

    public function show($id)
    {
        Gate::authorize('show_occupation');

        return $this->service->show($id);
    }


    public function update(StoreUpdateOccupationRequest $request)
    {
        Gate::authorize('update_occupation');

        return $this->service->update($request);
    }

    public function activate($id)
    {
        Gate::authorize('activate_occupation');

        return $this->service->activate($id);
    }

    public function inactivate($id)
    {
        Gate::authorize('inactivate_occupation');

        return $this->service->inactivate($id);
    }

    public function deleted($id)
    {
        Gate::authorize('deleted_occupation');

        return $this->service->deleted($id);
    }

    public function recover($id)
    {
        Gate::authorize('recover_occupation');

        return $this->service->recover($id);
    }

    public function destroy($id)
    {
        Gate::authorize('destroy_occupation');

        return $this->service->destroy($id);
    }

    public function dangersOccupation($id)
    {
        return $this->service->dangersOccupation($id);
    }

    public function attachDangers(Request $request)
    {
        return $this->service->attachDangers($request);
    }

    public function detachDangers(Request $request)
    {
        return $this->service->detachDangers($request);
    }
}
