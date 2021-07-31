<?php

namespace App\Repositories\Admin;

use App\Http\Resources\Admin\CompanyResource;
use App\Models\Admin\Company;
use App\Repositories\Admin\Contracts\CompanyRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CompanyRepository implements CompanyRepositoryInterface
{
    private $repository;

    public function __construct(Company $company)
    {
        $this->repository = $company;
    }

    public function search($request)
    {
        try {

            $active = !$request->active ? false : true;
            $search = $request->search;

            $companies = $this->repository->where('active', $active)
                ->where('deleted', false)
                ->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('fantasy_name', 'LIKE', "%{$search}%")
                    ->orWhere('cpf_cnpj', 'LIKE', "%{$search}%");
                })
                ->orderBy('contractor_id')
                ->orderBy('name')
                ->get();

            if (Auth::user()->contractor_id != 1) {
                $companies = $companies->where('contractor_id', Auth::user()->contractor_id);
            }

            return ['status' => true, 'data' => CompanyResource::collection($companies)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function store($request)
    {
        try {
            $data = $request->all();
            $data['uuid'] = Str::uuid();
            $data['contractor_id'] = $request->contractor_id ? $request->contractor_id : Auth::user()->contractor_id;

            $company = $this->repository->create($data);


            $company->phones()->create(['type' => 'Fixo', 'phone' => $request->phone]);
            $company->phones()->create(['type' => 'Celular', 'phone' => $request->phone_cell]);

            $company->emails()->create(['type' => 'Principal', 'email' => $request->email_main]);

            $company->addresses()->create($request->address);


            return ['status' => true, 'message' => 'A Empresa foi cadastrada.', 'data' => new CompanyResource($company)];
        } catch (\Throwable $th) {
            if ($company) {
                $company->phones()->delete();
                $company->emails()->delete();
                $company->delete();
            }
            return ['status' => false, 'message' => 'A Empresa não foi cadastrada.', 'error' => $th->getMessage()];
        }
    }

    public function show($uuid)
    {
        try {

            $company = $this->repository->where('uuid', $uuid)->first();

            return ['status' => true, 'data' => new CompanyResource($company)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update($request)
    {
        try {

            $data = $request->except(['phone', 'active', 'deleted', 'created_at']);

            $company = $this->repository->where('uuid', $request->uuid)->first();
            $company->update($data);

            return ['status' => true, 'data' => new CompanyResource($company), 'message' => 'A Empresa foi editada.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Empresa não foi editada.', 'error' => $th->getMessage()];
        }
    }

    public function activate($uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['active' => true]);

            $this->repository->where('uuid', $uuid)->first()->activateAudit('Empresas');

            return ['status' => true, 'message' => 'A Empresa foi ativada.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Empresa não foi ativada.', 'error' => $th->getMessage()];
        }
    }

    public function inactivate($uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['active' => false]);

            $this->repository->where('uuid', $uuid)->first()->inactivateAudit('Empresas');

            return ['status' => true, 'message' => 'A Empresa foi inativada.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Empresa não foi inativada.', 'error' => $th->getMessage()];
        }
    }

    public function deleted($uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['deleted' => true]);

            $this->repository->where('uuid', $uuid)->first()->deletedAudit('Empresas');

            return ['status' => true, 'message' => 'A Empresa foi deletada.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Empresa não foi deletada.', 'error' => $th->getMessage()];
        }
    }

    public function recover($uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['deleted' => false]);

            $this->repository->where('uuid', $uuid)->first()->recoverAudit('Empresas');

            return ['status' => true, 'message' => 'A Empresa foi recuperada.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Empresa não foi recuperada.', 'error' => $th->getMessage()];
        }
    }

    public function destroy($uuid)
    {
        try {
            $company = $this->repository->where('uuid', $uuid)->first();

            $company->users()->detach();
            $company->delete();

            return ['status' => true, 'message' => 'A Empresa foi excluída.'];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'A Empresa não foi excluída.', 'error' => $th->getMessage()];
        }
    }
}
