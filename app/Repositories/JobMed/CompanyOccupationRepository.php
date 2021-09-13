<?php

namespace App\Repositories\JobMed;

use App\Http\Resources\JobMed\CompanyOccupationResource;
use App\Http\Resources\JobMed\OccupationResource;
use App\Models\JobMed\CompanyOccupation;
use App\Models\JobMed\Occupation;
use App\Repositories\JobMed\Contracts\CompanyOccupationRepositoryInterface;

class CompanyOccupationRepository implements CompanyOccupationRepositoryInterface
{
    private $repository;

    public function __construct(CompanyOccupation $companyOccupation)
    {
        $this->repository = $companyOccupation;
    }

    public function index(int $id)
    {
        try {
            $occupations = $this->repository->where('company_id', $id)->get();

            return ['status' => true, 'data' => CompanyOccupationResource::collection($occupations)];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function search(int $company_id, string $search)
    {
       $occupationsId = $this->repository->where('company_id', $company_id)->pluck('occupation_id');

        try {
            $occupations  = Occupation::whereNotIn('id', $occupationsId)
                ->where('active', true)
                ->where('deleted', false)
                ->where('deleted', false)
                ->where('type', ' Ocupação')
                ->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%')
                        ->orWhere('code', 'LIKE', '%' . $search . '%');
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

            $this->repository->insert($data);

            return ['status' => true, 'message' => 'A Função foi cadastrada.'];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'A Função não foi cadastrada.', 'error' => $th->getMessage()];
        }
    }

    public function show(int $id)
    {
        try {
            $occupation = $this->repository->find($id);

            return ['status' => true, 'data' => new CompanyOccupationResource($occupation)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update(array $data)
    {
        try {
            $occupation = $this->repository->find($data['id']);
            $occupation->update($data);

            return ['status' => true, 'data' => new CompanyOccupationResource($occupation), 'message' => 'A Função foi editada.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Função não foi editada.', 'error' => $th->getMessage()];
        }
    }

    public function destroy(string $id)
    {
        try {
            $ids = explode(",", $id);
            $this->repository->whereIn('id', $ids)->delete();

            return ['status' => true, 'message' => 'As Funções foram excluídas.'];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'As Funções não foram excluídas.', 'error' => $th->getMessage()];
        }
    }
}
