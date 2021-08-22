<?php

namespace App\Services\Admin;

use App\Repositories\Admin\Contracts\CompanyRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\support\Str;

class CompanyService
{
    protected $repository;

    public function __construct(CompanyRepositoryInterface $interface)
    {
        $this->repository = $interface;
    }

    public function search(object $request)
    {
        $data = $request->all();
        $data['active'] = !$request->active ? false : true;

        return $this->repository->search($data);
    }

    public function store(object $request)
    {

        $data = $request->all();
        $data['contractor_id'] = $request->contractor_id ? $request->contractor_id : Auth::user()->contractor_id;
        $data['uuid'] = Str::uuid();

        return $this->repository->store($data);
    }

    public function show(string $uuid)
    {
        return $this->repository->show($uuid);
    }

    public function update(object $request)
    {
        $data = $request->except(['logo', 'active', 'deleted', 'created_at']);

        return $this->repository->update($data);
    }

    public function activate(string $uuid)
    {
        return $this->repository->activate($uuid);
    }

    public function inactivate(string $uuid)
    {
        return $this->repository->inactivate($uuid);
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

    public function companiesUser(string $uuid)
    {
        return $this->repository->companiesUser($uuid);
    }
}
