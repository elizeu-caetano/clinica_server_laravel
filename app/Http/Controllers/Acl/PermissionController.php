<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\PermissionRequest;
use App\Repositories\Acl\Contracts\PermissionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    private $repository;

    public function __construct(PermissionRepositoryInterface $repository, Request $request)
    {
       $this->repository = $repository;
    }

    public function search(Request $request)
    {
        // Gate::authorize($this->permission);
        
        return $this->repository->search($request);
    }

    public function store(PermissionRequest $request)
    {
        // Gate::authorize($this->permission);
        
        return $this->repository->store($request);
    }

    public function show($id)
    {
        // Gate::authorize($this->permission);
        
        return $this->repository->show($id);
    }


    public function update(PermissionRequest $request)
    {
        // Gate::authorize($this->permission);
        
        return $this->repository->update($request);
    }
    
    public function destroy($id)
    {
        // Gate::authorize($this->permission);
        
        return $this->repository->destroy($id);
    }
}
