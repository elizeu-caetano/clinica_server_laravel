<?php

namespace App\Repositories\JobMed;

use App\Http\Resources\JobMed\DangerResource;
use App\Models\JobMed\Danger;
use App\Repositories\JobMed\Contracts\DangerRepositoryInterface;

class DangerRepository implements DangerRepositoryInterface
{
    private $repository;

    public function __construct(Danger $danger)
    {
        $this->repository = $danger;
    }

    public function search(array $data)
    {
        try {
            $dangers  = $this->repository->where('active', $data['active'])
                ->where('deleted', false)
                ->where('name', 'LIKE', '%' . $data['search'] . '%')
                ->orderBy('name')->get();

            return ['status' => true, 'data' => DangerResource::collection($dangers)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function store(array $data)
    {
        try {
            $danger = $this->repository->create($data);

            return ['status' => true, 'message' => 'O Risco foi cadastrado.', 'data' => new DangerResource($danger)];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'O Risco não foi cadastrado.', 'error' => $th->getMessage()];
        }
    }

    public function show(int $id)
    {
        try {
            $danger = $this->repository->find($id);

            return ['status' => true, 'data' => new DangerResource($danger)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update(array $data)
    {
        try {
            $danger = $this->repository->find($data['id']);
            $danger->update($data);

            return ['status' => true, 'data' => new DangerResource($danger), 'message' => 'O Risco foi editado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Risco não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function activate(int $id)
    {
        try {
            $this->repository->find($id)->update(['active' => true]);

            $this->repository->find($id)->activateAudit('Riscos');

            return ['status' => true, 'message' => 'O Risco foi ativado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Risco não foi ativado.', 'error' => $th->getMessage()];
        }
    }

    public function inactivate(int $id)
    {
        try {
            $this->repository->find($id)->update(['active' => false]);

            $this->repository->find($id)->inactivateAudit('Riscos');

            return ['status' => true, 'message' => 'O Risco foi inativado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Risco não foi inativado.', 'error' => $th->getMessage()];
        }
    }

    public function deleted(int $id)
    {
        try {
            $this->repository->find($id)->update(['deleted' => true]);

            $this->repository->find($id)->first()->deletedAudit('Riscos');

            return ['status' => true, 'message' => 'O Risco foi deletado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Risco não foi deletado.', 'error' => $th->getMessage()];
        }
    }

    public function recover(int $id)
    {
        try {
            $this->repository->find($id)->update(['deleted' => false]);

            $this->repository->find($id)->first()->recoverAudit('Riscos');

            return ['status' => true, 'message' => 'O Risco foi recuperado.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Risco não foi recuperado.', 'error' => $th->getMessage()];
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->repository->find($id)->delete();

            return ['status' => true, 'message' => 'O Risco foi excluído.'];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'O Risco não foi excluído.', 'error' => $th->getMessage()];
        }
    }
}
