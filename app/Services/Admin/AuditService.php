<?php

namespace App\Services\Admin;

use App\Repositories\Admin\Contracts\AuditRepositoryInterface;

class AuditService
{
    protected $repository;

    public function __construct(AuditRepositoryInterface $interface)
    {
        $this->repository = $interface;
    }

    public function search(object $request)
    {
        $data = $request->only(['search']);
        if ($request->date) {
            $data['startDate']  = isset(explode(' ', $request->date)[0]) ? explode(' ', $request->date)[0] : $request->date;
            $data['endDate']  = isset(explode(' ', $request->date)[2]) ? explode(' ', $request->date)[2] : $request->date;
        } else {
            $data['startDate']  = date("Y-m-01");
            $data['endDate']  = date("Y-m-d");
        }

        return $this->repository->search($data);
    }

    public function show(int $id)
    {
        return $this->repository->show($id);
    }
}
