<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AuditController extends Controller
{
    protected $service;

    public function __construct(AuditService $service)
    {
        $this->service = $service;
    }

    public function search(Request $request)
    {
        Gate::authorize('search_audit');

        return $this->service->search($request);
    }

    public function show($id)
    {
        Gate::authorize('show_audit');

        return $this->service->show($id);
    }

}
