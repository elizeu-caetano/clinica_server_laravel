<?php

namespace App\Repositories\JobMed;

use App\Http\Resources\JobMed\DangerResource;
use App\Http\Resources\JobMed\OccupationResource;
use App\Models\JobMed\Danger;
use App\Models\JobMed\Occupation;
use App\Repositories\JobMed\Contracts\OccupationRepositoryInterface;

class OccupationRepository implements OccupationRepositoryInterface
{
    private $repository;

    public function __construct(Occupation $occupation)
    {
        $this->repository = $occupation;
    }

    public function search(array $data)
    {
        try {
            $occupations  = $this->repository->where('active', $data['active'])
                ->where('deleted', false)
                ->where(function ($query) use ($data) {
                    $query->where('name', 'LIKE', '%' . $data['search'] . '%')
                        ->orWhere('code', 'LIKE', '%' . $data['search'] . '%');
                })
                ->orderBy('name')->get();

            return ['status' => true, 'data' => OccupationResource::collection($occupations)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function store(array $data)
    {
        try {
            $occupation = $this->repository->create($data);

            return ['status' => true, 'message' => 'A Função foi cadastrada.', 'data' => new OccupationResource($occupation)];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'A Função não foi cadastrada.', 'error' => $th->getMessage()];
        }
    }

    public function show(int $id)
    {
        try {
            $occupation = $this->repository->find($id);

            return ['status' => true, 'data' => new OccupationResource($occupation)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update(array $data)
    {
        try {
            $occupation = $this->repository->find($data['id']);
            $occupation->update($data);

            return ['status' => true, 'data' => new OccupationResource($occupation), 'message' => 'A Função foi editada.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Função não foi editada.', 'error' => $th->getMessage()];
        }
    }

    public function activate(int $id)
    {
        try {
            $this->repository->find($id)->update(['active' => true]);

            $this->repository->find($id)->activateAudit('Funções');

            return ['status' => true, 'message' => 'A Função foi ativada.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Função não foi ativada.', 'error' => $th->getMessage()];
        }
    }

    public function inactivate(int $id)
    {
        try {
            $this->repository->find($id)->update(['active' => false]);

            $this->repository->find($id)->inactivateAudit('Funções');

            return ['status' => true, 'message' => 'A Função foi inativada.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Função não foi inativada.', 'error' => $th->getMessage()];
        }
    }

    public function deleted(int $id)
    {
        try {
            $this->repository->find($id)->update(['deleted' => true]);

            $this->repository->find($id)->first()->deletedAudit('Funções');

            return ['status' => true, 'message' => 'A Função foi deletada.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Função não foi deletada.', 'error' => $th->getMessage()];
        }
    }

    public function recover(int $id)
    {
        try {
            $this->repository->find($id)->update(['deleted' => false]);

            $this->repository->find($id)->first()->recoverAudit('Funções');

            return ['status' => true, 'message' => 'A Função foi recuperada.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Função não foi recuperada.', 'error' => $th->getMessage()];
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->repository->find($id)->delete();

            return ['status' => true, 'message' => 'A Função foi excluída.'];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'A Função não foi excluída.', 'error' => $th->getMessage()];
        }
    }

    public function dangersOccupation(int $id)
    {
        try {
            $occupation = $this->repository->find($id);
            $dangersId = $occupation->dangers()->pluck('id');

            $dangersOccupation = $occupation->dangers()->get();

            $dangersNotOccupation = Danger::whereNotIn('id', $dangersId)->get();

            return [
                'status'=> true,
                'dangersOccupation'=> DangerResource::collection($dangersOccupation),
                'dangersNotOccupation' => DangerResource::collection($dangersNotOccupation)
            ];

        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'Não foi possível carregar os riscos', 'error'=> $th->getMessage()];
        }
    }

    public function attachDangers(array $data)
    {
        try {
            $occupation = $this->repository->find($data['id']);

            $occupation->dangers()->attach($data['dangers']);

            return ['status'=>true, 'message'=> 'Riscos adicionados.'];

        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'Os Riscos não foram adicionados.', 'error'=> $th->getMessage()];
        }

    }

    public function detachDangers(array $data)
    {
        try {
            $occupation = $this->repository->find($data['id']);

            $occupation->dangers()->detach($data['dangers']);

            return ['status'=>true, 'message'=> 'Riscos excluidos.'];

        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'As Riscos não foram excluidos.', 'error'=> $th->getMessage()];
        }

    }
}
