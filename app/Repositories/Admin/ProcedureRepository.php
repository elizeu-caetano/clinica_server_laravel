<?php

namespace App\Repositories\Admin;

use App\Http\Resources\Admin\ProcedureResource;
use App\Models\Admin\Procedure;
use App\Repositories\Admin\Contracts\ProcedureRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class ProcedureRepository implements ProcedureRepositoryInterface
{
    private $repository;

    public function __construct(Procedure $procedure)
    {
        $this->repository = $procedure;
    }

    public function search(array $data)
    {
        try {
            $prodedure = $this->repository->with(['procedureGroup', 'contractor'])
                                ->where('deleted', false)
                                ->where('active', $data['active'])
                                ->where('name', 'LIKE', '%'.$data['search'].'%')
                                ->where(function ($query){
                                    if ((Auth::user()->contractor_id != 1)) {
                                        $query->where('contractor_id', Auth::user()->contractor_id);
                                    }
                                })
                                ->orderBy('name')->get();

            return ['status' => true, 'data' => ProcedureResource::collection($prodedure)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function store(array $data)
    {
        try {
            $procedure = $this->repository->create($data);

            return ['status' => true, 'message' => 'O Procedimento foi cadastrado.', 'data' => new ProcedureResource($procedure)];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'O Procedimento não foi cadastrado.', 'error' => $th->getMessage()];
        }
    }

    public function show(string $uuid)
    {
        try {
            $procedure = $this->repository->where('uuid', $uuid)->first();

            return ['status' => true, 'data' => new ProcedureResource($procedure)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update(array $data)
    {
        try {

            $procedure = $this->repository->where('uuid', $data['uuid'])->first();
            $procedure->update($data);

            return ['status' => true, 'data' => new ProcedureResource($procedure), 'message' => 'O Procedimento foi editado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Procedimento não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function activate(string $uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['active' => true]);

            $this->repository->where('uuid', $uuid)->first()->activateAudit('Empresas');

            return ['status' => true, 'message' => 'A Empresa foi ativada.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Empresa não foi ativada.', 'error' => $th->getMessage()];
        }
    }

    public function inactivate(string $uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['active' => false]);

            $this->repository->where('uuid', $uuid)->first()->inactivateAudit('Empresas');

            return ['status' => true, 'message' => 'A Empresa foi inativada.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Empresa não foi inativada.', 'error' => $th->getMessage()];
        }
    }

    public function deleted(string $uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['deleted' => true]);

            $this->repository->where('uuid', $uuid)->first()->deletedAudit('Procedimentos');

            return ['status' => true, 'message' => 'O Procedimentos foi deletado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Procedimentos não foi deletado.', 'error' => $th->getMessage()];
        }
    }

    public function recover(string $uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['deleted' => false]);

            $this->repository->where('uuid', $uuid)->first()->recoverAudit('Procedimentos');

            return ['status' => true, 'message' => 'O Procedimentos foi recuperado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Procedimentos não foi recuperado.', 'error' => $th->getMessage()];
        }
    }

    public function destroy(string $uuid)
    {
        try {
            $procedure = $this->repository->where('uuid', $uuid)->first();

            $procedure->delete();

            return ['status' => true, 'message' => 'O Procedimentos foi excluído.'];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'O Procedimentos não foi excluído.', 'error' => $th->getMessage()];
        }
    }
}
