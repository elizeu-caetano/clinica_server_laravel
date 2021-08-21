<?php

namespace App\Repositories\Admin;

use App\Http\Resources\Admin\AuditResource;
use App\Models\Admin\Audit;
use App\Repositories\Admin\Contracts\AuditRepositoryInterface;
use Carbon\Carbon;

class AuditRepository implements AuditRepositoryInterface
{
    private $repository;

    public function __construct(Audit $model)
    {
        $this->repository = $model;
    }

    public function search($request)
    {
        try {
            if ($request->date) {
                $startDate  = isset(explode(' ', $request->date)[0]) ? explode(' ', $request->date)[0] : $request->date;
                $endDate  = isset(explode(' ', $request->date)[2]) ? explode(' ', $request->date)[2] : $request->date;
            } else {
                $startDate  = date("Y-m-01");
                $endDate  = date("Y-m-d");
            }

            $search = $request->search;

            $audits =  $this->repository->with('auditable')
                        ->whereDate('created_at', '>=', $startDate)
                        ->whereDate('created_at', '<=', $endDate)
                        ->where('tags', 'LIKE', "%{$search}%")
                        ->orderBy('created_at', 'desc')->get();

            return ['status' => true, 'data' => AuditResource::collection($audits)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function show($id)
    {
        try {
            $entity =  $this->repository->find($id);

            return ['status' => true, 'data' => new AuditResource($entity)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }
}
