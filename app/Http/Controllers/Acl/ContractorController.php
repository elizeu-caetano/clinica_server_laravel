<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\ContractorRequest;
use App\Repositories\Acl\Contracts\ContractorRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ContractorController extends Controller
{
    private $repository;

    public function __construct(ContractorRepositoryInterface $repository, Request $request)
    {
       $this->repository = $repository;
    }

    public function index()
    {   
        Gate::authorize('store_contractor');

        return $this->repository->index();
    }

    public function search(Request $request)
    {   
        Gate::authorize('search_contractor');

        return $this->repository->search($request);
    }

    public function store(ContractorRequest $request)
    {
        Gate::authorize('store_contractor');

        return $this->repository->store($request);
    }

    public function show($uuid)
    {
        Gate::authorize('show_contractor');

        return $this->repository->show($uuid);
    }


    public function update(ContractorRequest $request)
    {
       Gate::authorize('update_contractor');

        return $this->repository->update($request);
    }

    public function activate($uuid)
    {
        Gate::authorize('activate_contractor');

        return $this->repository->activate($uuid);
    }

    public function inactivate($uuid)
    {
        Gate::authorize('inactivate_contractor');

        return $this->repository->inactivate($uuid);
    }
    
    public function destroy($uuid)
    {
        Gate::authorize('destroy_contractor');

        return $this->repository->destroy($uuid);
    }
}
