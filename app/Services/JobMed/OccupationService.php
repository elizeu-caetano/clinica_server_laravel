<?php

namespace App\Services\JobMed;

use App\Repositories\JobMed\Contracts\OccupationRepositoryInterface;

class OccupationService
{
    protected $repository;

    public function __construct(OccupationRepositoryInterface $interface)
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
        $data['code'] = preg_replace('/[^0-9]/', '', $request->code);

        return $this->repository->store($data);
    }

    public function show(int $id)
    {
        return $this->repository->show($id);
    }

    public function update(object $request)
    {
        $data = $request->except(['created_at', 'cbo']);
        $data['code'] = preg_replace('/[^0-9]/', '', $request->code);

        return $this->repository->update($data);
    }

    public function activate(int $id)
    {
        return $this->repository->activate($id);
    }

    public function inactivate(int $id)
    {
        return $this->repository->inactivate($id);
    }

    public function deleted(int $id)
    {
        return $this->repository->deleted($id);
    }

    public function recover(int $id)
    {
        return $this->repository->show($id);
    }

    public function destroy(int $id)
    {
        return $this->repository->destroy($id);
    }

    public function dangersOccupation(int $id)
    {
        return $this->repository->dangersOccupation($id);
    }

    public function attachDangers(object $request)
    {
        $data = $request->all();
        return $this->repository->attachDangers($data);
    }

    public function detachDangers(object $request)
    {
        $data = $request->all();
        return $this->repository->detachDangers($data);
    }
}
