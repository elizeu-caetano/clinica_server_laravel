<?php

namespace App\Http\Controllers\JobMed;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobMed\StoreUpdateDangerRequest;
use App\Services\JobMed\DangerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DangerController extends Controller
{
    private $service;

    public function __construct(DangerService $service)
    {
       $this->service = $service;
    }

    public function search(Request $request)
    {
        Gate::authorize('search_danger');

        return $this->service->search($request);
    }

    public function store(StoreUpdateDangerRequest $request)
    {
        Gate::authorize('store_danger');

        return $this->service->store($request);
    }

    public function show($id)
    {
        Gate::authorize('show_danger');

        return $this->service->show($id);
    }


    public function update(StoreUpdateDangerRequest $request)
    {
        Gate::authorize('update_danger');

        return $this->service->update($request);
    }

    public function activate($id)
    {
        Gate::authorize('activate_danger');

        return $this->service->activate($id);
    }

    public function inactivate($id)
    {
        Gate::authorize('inactivate_danger');

        return $this->service->inactivate($id);
    }

    public function deleted($id)
    {
        Gate::authorize('deleted_danger');

        return $this->service->deleted($id);
    }

    public function recover($id)
    {
        Gate::authorize('recover_danger');

        return $this->service->recover($id);
    }

    public function destroy($id)
    {
        Gate::authorize('destroy_danger');

        return $this->service->destroy($id);
    }
}
