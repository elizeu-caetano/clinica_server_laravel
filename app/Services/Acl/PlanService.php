<?php

namespace App\Services\Acl;

use App\Repositories\Acl\Contracts\PlanRepositoryInterface;
use Illuminate\support\Str;

class PlanService
{
    protected $planService;

    public function __construct(PlanRepositoryInterface $planService)
    {
        $this->planService = $planService;
    }

    public function search(object $request)
    {
        $data = $request->all();
        $data['active'] = !$request->active ? false : true;

        return $this->planService->search($data);
    }

    public function store(object $request)
    {
        $data = $request->all();
        $data['uuid'] = Str::uuid();

        return $this->planService->store($data);
    }

    public function show(string $uuid)
    {
        return $this->planService->show($uuid);
    }

    public function update(object $request)
    {
        $data = $request->except(['active', 'created_at']);

        return $this->planService->update($data);
    }

    public function activate(string $uuid)
    {
        return $this->planService->activate($uuid);
    }

    public function inactivate(string $uuid)
    {
        return $this->planService->inactivate($uuid);
    }

    public function destroy(string $uuid)
    {
        return $this->planService->destroy($uuid);
    }

    public function planPermissions(string $uuid)
    {
        return $this->planService->planPermissions($uuid);
    }

    public function attachPermissions(object $request)
    {
        $data = $request->all();
        return $this->planService->attachPermissions($data);
    }

    public function detachPermissions(object $request)
    {
        $data = $request->all();
        return $this->planService->detachPermissions($data);
    }
}
