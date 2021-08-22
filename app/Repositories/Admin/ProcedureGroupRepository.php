<?php

namespace App\Repositories\Admin;

use App\Http\Resources\Admin\ProcedureGroupResource;
use App\Models\Admin\ProcedureGroup;
use App\Repositories\Admin\Contracts\ProcedureGroupRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProcedureGroupRepository implements ProcedureGroupRepositoryInterface
{
    private $repository;

    public function __construct(ProcedureGroup $model)
    {
        $this->repository = $model;
    }

    public function search(array $data)
    {
        try {
            $prodedureGroups = $this->repository->where('deleted', false)
                                ->where('name', 'LIKE', '%'.$data['search'].'%')
                                ->where(function ($query){
                                    if ((Auth::user()->contractor_id != 1)) {
                                        $query->where('contractor_id', Auth::user()->contractor_id);
                                    }
                                })
                                ->orderBy('name');

            return ['status' => true, 'data' => ProcedureGroupResource::collection($prodedureGroups->get())];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function store(array $data)
    {
        try {

            $procedureGroup = $this->repository->create($data);

            return ['status' => true, 'message' => 'O Grupo de Procedimento foi cadastrado.', 'data' => new ProcedureGroupResource($procedureGroup)];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'O Grupo de Procedimento não foi cadastrado.', 'error' => $th->getMessage()];
        }
    }

    public function show(string $uuid)
    {
        try {
            $procedureGroup = $this->repository->where('uuid', $uuid)->first();

            return ['status' => true, 'data' => new ProcedureGroupResource($procedureGroup)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update(array $data)
    {
        try {

            $procedureGroup = $this->repository->where('uuid', $data['uuid'])->first();
            $procedureGroup->update($data);

            return ['status' => true, 'data' => new ProcedureGroupResource($procedureGroup), 'message' => 'O Grupo de Procedimento foi editado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Grupo de Procedimento não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function deleted(string $uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['deleted' => true]);

            $this->repository->where('uuid', $uuid)->first()->deletedAudit('Grupo de Procedimentos');

            return ['status' => true, 'message' => 'O Grupo de Procedimentos foi deletado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Grupo de Procedimentos não foi deletado.', 'error' => $th->getMessage()];
        }
    }

    public function recover(string $uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['deleted' => false]);

            $this->repository->where('uuid', $uuid)->first()->recoverAudit('Grupo de Procedimentos');

            return ['status' => true, 'message' => 'O Grupo de Procedimentos foi recuperado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Grupo de Procedimentos não foi recuperado.', 'error' => $th->getMessage()];
        }
    }

    public function destroy(string $uuid)
    {
        try {
            $procedureGroup = $this->repository->where('uuid', $uuid)->first();

            $procedureGroup->delete();

            return ['status' => true, 'message' => 'O Grupo de Procedimentos foi excluído.'];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'O Grupo de Procedimentos não foi excluído.', 'error' => $th->getMessage()];
        }
    }
}
