<?php

namespace App\Repositories\Acl;

use App\Http\Resources\Acl\ContractorResource;
use App\Http\Resources\Acl\PlanResource;
use App\Models\Acl\Contractor;
use App\Models\Acl\Plan;
use App\Models\Acl\Role;
use App\Repositories\Acl\Contracts\ContractorRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ContractorRepository implements ContractorRepositoryInterface {

    private $repository;

    public function __construct(Contractor $contractor)
    {
        $this->repository = $contractor;
    }

    public function index()
    {
        try {

            $plans = DB::table('plans')->select('id as plan_id', 'name')->where('id', '>', 2)->get();
            $data['plans'] = $plans;

            return ['status' => true, 'data' => $data];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function search(array $data)
    {
        try {
            $contractors = $this->repository->where('active', $data['active'])
            ->where('deleted', false)
            ->where(function ($query) use ($data) {
                $query->where('name', 'LIKE', '%'.$data['search'].'%')
                ->orWhere('fantasy_name', 'LIKE', '%'.$data['search'].'%')
                ->orWhere('cpf_cnpj', 'LIKE', '%'.$data['search'].'%');
            })->get();

            return ['status' => true, 'data' => ContractorResource::collection($contractors)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function store(array $data)
    {
        try {
            $contractor = $this->repository->create($data);
            $contractor->plans()->attach($data['plan_ids']);

            $this->attachPermissiosRole($contractor);

            return ['status' => true, 'message' => 'O Contratante foi cadastrado.', 'data' => new ContractorResource($contractor)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Contratante não foi cadastrado.', 'error' => $th->getMessage()];
        }
    }

    private function attachPermissiosRole($contractor)
    {
        $idPlans = $contractor->plans->pluck('id')->toArray();

        $idPermissionsPlans = DB::table('permission_plan')->whereIn('plan_id', $idPlans)->pluck('permission_id')->toArray();

        $permissions =  array_unique($idPermissionsPlans);

        $role = Role::where('contractor_id', $contractor->id)->first();

        $role->permissions()->attach($permissions);
    }

    public function show(string $uuid)
    {
        try {
            $contractors = $this->repository->where('uuid', $uuid)->with('plans')->first();

            return ['status' => true, 'data' => new ContractorResource($contractors)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update(array $data)
    {
        try {
            $contractor = $this->repository->where('uuid', $data['uuid'])->first();

            $contractor->update($data);

            return ['status' => true, 'message' => 'O Contratante foi editado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Contratante não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function activate(string $uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['active' => true]);

            $this->repository->where('uuid', $uuid)->first()->activateAudit('Contratantes');

            return ['status' => true, 'message' => 'O Contratante foi ativado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Contratante não foi ativado.', 'error' => $th->getMessage()];
        }
    }

    public function inactivate(string $uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['active' => false]);

            $this->repository->where('uuid', $uuid)->first()->inactivateAudit('Contratantes');

            return ['status' => true, 'message' => 'O Contratante foi inativado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Contratante não foi inativado.', 'error' => $th->getMessage()];
        }
    }

    public function destroy(string $uuid)
    {
        try {
           $contractor = $this->repository->where('uuid', $uuid)->first();

            if ($contractor->logo != '') {
                Storage::disk('s3')->delete($contractor->logo);
            }

            $contractor->plans()->detach();
            $roles = $contractor->roles;
            foreach ($roles as $role) {
                $role->permissions()->detach();
            }

            $contractor->delete();

            return ['status'=> true, 'message'=> 'O Contratante foi excluído.'];

        } catch (\Throwable $th) {

            return ['status'=> false, 'message'=> 'O Contratante não foi excluído', 'error'=> $th->getMessage()];
        }
    }

    public function uploadLogo(object $contractor, string $path)
    {
        try {

            $contractor->update(['logo' => $path]);

            return ['status' => true, 'message' => 'A logo foi adicionada.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A logo não foi adicionada.', 'error' => $th->getMessage()];
        }
    }

    public function contractorPlans(string $uuid)
    {
        try {
            $contractor = $this->repository->where('uuid', $uuid)->first();
            $contractorsId = $contractor->plans()->pluck('id');

            $plansContractor = $contractor->plans()->get();

            $notPlansContractor = Plan::whereNotIn('id', $contractorsId)->get();

            return ['status'=>true, 'plansContractor'=> PlanResource::collection($plansContractor), 'notPlansContractor' => PlanResource::collection($notPlansContractor)];

        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'Não foi possível carregar os planos', 'error'=> $th->getMessage()];
        }
    }

    public function attachPlans(array $request)
    {
        try {
            $contractor = $this->repository->where('uuid', $request['uuid'])->first();

            $contractor->plans()->attach($request['plans']);

            return ['status'=>true, 'message'=> 'Planos adicionados.'];

        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'Os planos não foram adicionados.', 'error'=> $th->getMessage()];
        }

    }

    public function detachPlans(array $request)
    {
        try {
            $contractor = $this->repository->where('uuid', $request['uuid'])->first();

            $contractor->plans()->detach($request['plans']);

            return ['status'=>true, 'message'=> 'Planos excluidos.'];

        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'As Planos não foram excluidos.', 'error'=> $th->getMessage()];
        }

    }
}
