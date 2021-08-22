<?php

namespace App\Repositories\Admin;

use App\Http\Resources\Admin\CompanyResource;
use App\Models\Acl\User;
use App\Models\Admin\Company;
use App\Repositories\Admin\Contracts\CompanyRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CompanyRepository implements CompanyRepositoryInterface
{
    private $repository;

    public function __construct(Company $company)
    {
        $this->repository = $company;
    }

    public function search(array $data)
    {
        try {
            $companies = $this->repository->where('active', $data['active'])
                        ->where('deleted', false)
                        ->where(function ($query) {
                            if (Auth::user()->contractor_id != 1) {
                                $query->where('contractor_id', Auth::user()->contractor_id);
                            }
                        })
                        ->where(function ($query) use ($data) {
                                $query->where('name', 'LIKE', '%'.$data['search'].'%')
                                ->orWhere('fantasy_name', 'LIKE', '%'.$data['search'].'%')
                                ->orWhere('cpf_cnpj', 'LIKE', '%'.$data['search'].'%');
                        })
                        ->orderBy('contractor_id')
                        ->orderBy('name');

            return ['status' => true, 'data' => CompanyResource::collection($companies->get())];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function store(array $data)
    {
        try {
            $company = $this->repository->create($data);

            $company->phones()->create(['type' => 'Fixo', 'phone' => $data['phone']]);
            $company->phones()->create(['type' => 'Celular', 'phone' => $data['phone_cell']]);

            $company->emails()->create(['type' => 'Principal', 'email' => $data['email']]);

            $company->addresses()->updateOrCreate(['type' => 'Residencial'], $data['address']);


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

    public function show(string $uuid)
    {
        try {

            $company = $this->repository->where('uuid', $uuid)->first();

            return ['status' => true, 'data' => new CompanyResource($company)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update(array $data)
    {
        try {
            $company = $this->repository->where('uuid', $data['uuid'])->first();
            $company->update($data);

            $company->phones()->updateOrCreate(['type' => 'Fixo'], ['phone' => $data['phone']]);
            $company->phones()->updateOrCreate(['type' => 'Celular'], ['phone' => $data['phone_cell']]);

            $company->emails()->updateOrCreate(['type' => 'Principal'], ['email' => $data['email']]);

            $company->addresses()->updateOrCreate(['type' => 'Residencial'], $data['address']);

            return ['status' => true, 'data' => new CompanyResource($company), 'message' => 'A Empresa foi editada.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Empresa não foi editada.', 'error' => $th->getMessage()];
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

            $this->repository->where('uuid', $uuid)->first()->deletedAudit('Empresas');

            return ['status' => true, 'message' => 'A Empresa foi deletada.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Empresa não foi deletada.', 'error' => $th->getMessage()];
        }
    }

    public function recover(string $uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['deleted' => false]);

            $this->repository->where('uuid', $uuid)->first()->recoverAudit('Empresas');

            return ['status' => true, 'message' => 'A Empresa foi recuperada.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Empresa não foi recuperada.', 'error' => $th->getMessage()];
        }
    }

    public function destroy(string $uuid)
    {
        try {
            $company = $this->repository->where('uuid', $uuid)->first();

            $company->users()->delete();
            $company->phones()->delete();
            $company->emails()->delete();
            $company->addresses()->delete();
            $company->delete();

            return ['status' => true, 'message' => 'A Empresa foi excluída.'];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'A Empresa não foi excluída.', 'error' => $th->getMessage()];
        }
    }


    public function companiesUser(string $uuid)
    {
        try {
            $user = User::where('uuid', $uuid)->first();
            $companies = $user->companies()->where('active', true)->where('deleted', false)->get();
            $notCompanies = Company::where('active', true)->where('deleted', false)->whereNotIn('id', $companies->pluck('id'));

            if (Auth::user()->contractor_id != 1) {
                $companies = $companies->where('contractor_id', Auth::user()->contractor_id);
                $notCompanies = $notCompanies->where('contractor_id', Auth::user()->contractor_id);
            }

            return ['status' => true, 'companies' => CompanyResource::collection($companies), 'notCompanies' => $notCompanies->get()];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'A Empresa não foi adicionada', 'error' => $th->getMessage()];
        }
    }
}
