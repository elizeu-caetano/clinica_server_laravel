<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\Contracts\AuditRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AuditController extends Controller
{
    private $repository;

    public function __construct(AuditRepositoryInterface $repository)
    {
       $this->repository = $repository;
    }

    public function search(Request $request)
    {
        Gate::authorize('search_audit');

        return $this->repository->search($request);
    }

    public function show($id)
    {
        Gate::authorize('show_audit');

        return $this->repository->show($id);
    }

}
