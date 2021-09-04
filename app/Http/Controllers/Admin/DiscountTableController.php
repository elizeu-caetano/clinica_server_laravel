<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUpdateDiscountTableRequest;
use App\Services\Admin\DiscountTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DiscountTableController extends Controller
{
    private $service;

    public function __construct(DiscountTableService $service)
    {
       $this->service = $service;
    }

    public function search(Request $request)
    {
        Gate::authorize('search_discount_table');

        return $this->service->search($request);
    }

    public function store(StoreUpdateDiscountTableRequest $request)
    {
        Gate::authorize('store_discount_table');

        return $this->service->store($request);
    }

    public function show($uuid)
    {
        Gate::authorize('show_discount_table');

        return $this->service->show($uuid);
    }


    public function update(StoreUpdateDiscountTableRequest $request)
    {
        Gate::authorize('update_discount_table');

        return $this->service->update($request);
    }

    public function activate($uuid)
    {
        Gate::authorize('activate_discount_table');

        return $this->service->activate($uuid);
    }

    public function inactivate($uuid)
    {
        Gate::authorize('inactivate_discount_table');

        return $this->service->inactivate($uuid);
    }

    public function deleted($uuid)
    {
        Gate::authorize('deleted_discount_table');

        return $this->service->deleted($uuid);
    }

    public function recover($uuid)
    {
        Gate::authorize('recover_discount_table');

        return $this->service->recover($uuid);
    }

    public function destroy($uuid)
    {
        Gate::authorize('destroy_discount_table');

        return $this->service->destroy($uuid);
    }
}
