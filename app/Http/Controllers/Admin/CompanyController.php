<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompanyRequest;
use App\Repositories\Admin\Contracts\CompanyRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CompanyController extends Controller
{
    private $repository;

    public function __construct(CompanyRepositoryInterface $repository)
    {
       $this->repository = $repository;
    }

    public function search(Request $request)
    {
        Gate::authorize('search_company');

        return $this->repository->search($request);
    }

    public function store(CompanyRequest $request)
    {
        Gate::authorize('store_company');

        return $this->repository->store($request);
    }

    public function show($uuid)
    {
        Gate::authorize('show_company');

        return $this->repository->show($uuid);
    }


    public function update(CompanyRequest $request)
    {
        Gate::authorize('update_company');

        return $this->repository->update($request);
    }

    public function activate($uuid)
    {
        Gate::authorize('activate_company');

        return $this->repository->activate($uuid);
    }

    public function inactivate($uuid)
    {
        Gate::authorize('inactivate_company');

        return $this->repository->inactivate($uuid);
    }

    public function deleted($uuid)
    {
        Gate::authorize('deleted_company');

        return $this->repository->deleted($uuid);
    }

    public function recover($uuid)
    {
        Gate::authorize('recover_company');

        return $this->repository->recover($uuid);
    }

    public function destroy($uuid)
    {
        Gate::authorize('destroy_company');

        return $this->repository->destroy($uuid);
    }

    public function companiesUser($uuid)
    {
        if(Gate::none(['add_company_user', 'remove_company_user'])){
            abort(403);
        }

        return $this->repository->companiesUser($uuid);
    }
}
