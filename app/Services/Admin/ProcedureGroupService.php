<?php

namespace App\Services\Admin;

use App\Repositories\Admin\Contracts\ProcedureGroupRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\support\Str;

class ProcedureGroupService
{
    protected $repository;

    public function __construct(ProcedureGroupRepositoryInterface $interface)
    {
        $this->repository = $interface;
    }

    public function search(object $request)
    {
        $data = $request->all();

        return $this->repository->search($data);
    }

    public function store(object $request)
    {
        $data = $request->all();
        $data['uuid'] = Str::uuid();
        $data['contractor_id'] = Auth::user()->contractor_id;

        return $this->repository->store($data);
    }

    public function show(string $uuid)
    {
        return $this->repository->show($uuid);
    }

    public function update(object $request)
    {
        $data = $request->except(['deleted', 'created_at']);

        return $this->repository->update($data);
    }

    public function deleted(string $uuid)
    {
        return $this->repository->deleted($uuid);
    }

    public function recover(string $uuid)
    {
        return $this->repository->show($uuid);
    }

    public function destroy(string $uuid)
    {
        return $this->repository->destroy($uuid);
    }
}
