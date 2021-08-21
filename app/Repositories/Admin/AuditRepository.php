<?php

namespace App\Repositories\Admin;

use App\Http\Resources\Admin\AuditResource;
use App\Models\Admin\Audit;
use App\Repositories\Admin\Contracts\AuditRepositoryInterface;

class AuditRepository implements AuditRepositoryInterface
{
    protected $entity;

    public function __construct(Audit $model)
    {
        $this->entity = $model;
    }

    public function search($data)
    {
        try {
            $audits =  $this->entity->with('auditable')
                        ->whereDate('created_at', '>=', $data->startDate)
                        ->whereDate('created_at', '<=', $data->endDate)
                        ->where('tags', 'LIKE', "%{$data->search}%")
                        ->orderBy('created_at', 'desc')->get();

            return ['status' => true, 'data' => AuditResource::collection($audits)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function show($id)
    {
        try {
            $entity =  $this->entity->find($id);

            return ['status' => true, 'data' => new AuditResource($entity)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }
}
