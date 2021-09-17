<?php

namespace App\Services\JobMed;

use App\Models\JobMed\CompanyOccupation;
use App\Repositories\JobMed\Contracts\CompanyOccupationRepositoryInterface;

class CompanyOccupationService
{
    protected $repository;

    public function __construct(CompanyOccupationRepositoryInterface $interface)
    {
        $this->repository = $interface;
    }

    public function index(int $id)
    {
        return $this->repository->index($id);
    }

    public function search(object $request)
    {
        $company_id = $request->company_id;
        $search = (string) $request->search;

        return $this->repository->search($company_id, $search);
    }

    public function store(object $request)
    {
        $data = [];
        foreach ($request->occupation_id as $occupation_id) {
            $occupation = CompanyOccupation::where('company_id', $request->company_id)->where('occupation_id', $occupation_id)->first();
            if ($occupation) {
                return ['status' => false, 'message' => 'A Função já esta cadastrada.'];
            }
            array_push($data, [
                'company_id' => $request->company_id,
                'occupation_id' => $occupation_id,
                'created_at' => now(),
            ]);
        }

        return $this->repository->store($data);
    }

    public function show(int $id)
    {
        return $this->repository->show($id);
    }

    public function update(object $request)
    {
        $id = $request->occupation_id;
        $procedureIds = [];
        $dangerIds = [];

        foreach ($request->procedures as $procedure) {
            if ($procedure['hired']) {
                array_push($procedureIds, $procedure['id']);
            }
        }

        foreach ($request->dangers as $danger) {
            if ($danger['hired']) {
                array_push($dangerIds, $danger['id']);
            }
        }

        return $this->repository->update($id, $procedureIds, $dangerIds);
    }

    public function destroy(string $id)
    {
        return $this->repository->destroy($id);
    }
}
