<?php

namespace App\Http\Controllers\Acl;
use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\RoleRequest;
use App\Repositories\Acl\Contracts\RoleRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    private $repository;

    public function __construct(RoleRepositoryInterface $repository, Request $request)
    {
       $this->repository = $repository;
    }

    public function search(Request $request)
    {   
        Gate::authorize('search_role');

        return $this->repository->search($request);
    }

    public function store(RoleRequest $request)
    {   
        Gate::authorize('store_role');

        return $this->repository->store($request);
    }

    public function show($id)
    {   
        Gate::authorize('show_role');

        return $this->repository->show($id);
    }


    public function update(RoleRequest $request)
    {   
        Gate::authorize('update_role');

        return $this->repository->update($request);
    }

    public function activate($id)
    {   
        Gate::authorize('activate_role');

        return $this->repository->activate($id);
    }

    public function inactivate($id)
    {   
        Gate::authorize('inactivate_role');

        return $this->repository->inactivate($id);
    }

    public function deleted($id)
    {   
        Gate::authorize('deleted_role');

        return $this->repository->deleted($id);
    }

    public function recover($id)
    {   
        Gate::authorize('recover_role');

        return $this->repository->recover($id);
    }
    
    public function destroy($id)
    {   
        Gate::authorize('destroy_role');

        return $this->repository->destroy($id);
    }
}
