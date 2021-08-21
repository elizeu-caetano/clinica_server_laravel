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

    public function search($data)
    {
        if ($data->date) {
            $startDate  = isset(explode(' ', $data->date)[0]) ? explode(' ', $data->date)[0] : $data->date;
            $endDate  = isset(explode(' ', $data->date)[2]) ? explode(' ', $data->date)[2] : $data->date;
        } else {
            $startDate  = date("Y-m-01");
            $endDate  = date("Y-m-d");
        }

        $data = $data->merge(['startDate' => $startDate, 'endDate' => $endDate]);

        return $this->repository->search($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }
}
