<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\PlanRequest;
use App\Repositories\Acl\Contracts\PlanRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PlanController extends Controller
{
    private $repository;
    private $permission;

    public function __construct(PlanRepositoryInterface $repository, Request $request)
    {
       $this->repository = $repository;      
    }

    public function search(Request $request)
    {
       // Gate::authorize($this->permission);

        return $this->repository->search($request);
    }

    public function store(PlanRequest $request)
    {
       // Gate::authorize($this->permission);

        return $this->repository->store($request);
    }

    public function show($id)
    {
       // Gate::authorize($this->permission);

        return $this->repository->show($id);
    }


    public function update(PlanRequest $request)
    {
       // Gate::authorize($this->permission);

        return $this->repository->update($request);
    }

    public function activate($id)
    {
       // Gate::authorize($this->permission);

        return $this->repository->activate($id);
    }

    public function inactivate($id)
    {
       // Gate::authorize($this->permission);

        return $this->repository->inactivate($id);
    }    
    
    public function destroy($id)
    {
       // Gate::authorize($this->permission);

        return $this->repository->destroy($id);
    }
}
