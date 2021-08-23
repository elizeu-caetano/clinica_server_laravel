<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\PermissionRequest;
use App\Services\Acl\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    private $permissionService;

    public function __construct(PermissionService $permissionService)
    {
       $this->permissionService = $permissionService;
    }

    public function search(Request $request)
    {
        Gate::authorize('search_permission');

        return $this->permissionService->search($request);
    }

    public function store(PermissionRequest $request)
    {
        Gate::authorize('store_permission');

        return $this->permissionService->store($request);
    }

    public function show($id)
    {
        Gate::authorize('show_permission');

        return $this->permissionService->show($id);
    }


    public function update(PermissionRequest $request)
    {
        Gate::authorize('update_permission');

        return $this->permissionService->update($request);
    }

    public function destroy($id)
    {
        Gate::authorize('destroy_permission');

        return $this->permissionService->destroy($id);
    }
}
