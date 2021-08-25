<?php

namespace App\Repositories\Acl;

use App\Http\Resources\Acl\PlanResource;
use App\Models\Acl\Permission;
use App\Models\Acl\Plan;
use App\Repositories\Acl\Contracts\PlanRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PlanRepository implements PlanRepositoryInterface {

    private $servicePlan;

    public function __construct(Plan $plan)
    {
        $this->servicePlan = $plan;
    }

    public function search(array $data)
    {
        try {
            $plans = $this->servicePlan->with(['plans'])
            ->where('active', $data['active'])
            ->where(function ($query) use ($data) {
                $query->where('name', 'LIKE', '%'.$data['search'].'%')
                ->orWhere('price', 'LIKE', '%'.$data['search'].'%');
            })->get();

            return ['status' => true, 'data' => PlanResource::collection($plans)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function store(array $data)
    {
        try {
            $plan = $this->servicePlan->create($data);

            return ['status' => true, 'message' => 'O Plano foi cadastrado.', 'data' => new PlanResource($plan)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Plano não foi cadastrado.', 'error' => $th->getMessage()];
        }
    }

    public function show(string $uuid)
    {
        try {
            $plan = $this->servicePlan->where('uuid', $uuid)->first();

            return ['status' => true, 'data' => new PlanResource($plan)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update(array $data)
    {
        try {
            $plan = $this->servicePlan->where('uuid', $data['uuid'])->first();
            $plan->update($data);

            return ['status' => true, 'data' => new PlanResource($plan), 'message' => 'O Plano foi editado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Plano não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function activate(string $uuid)
    {
        try {
            $this->servicePlan->where('uuid', $uuid)->update(['active' => true]);

            $this->servicePlan->where('uuid', $uuid)->first()->activateAudit('Planos');

            return ['status' => true, 'message' => 'O Plano foi ativado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Plano não foi ativado.', 'error' => $th->getMessage()];
        }
    }

    public function inactivate(string $uuid)
    {
        try {
            $this->servicePlan->where('uuid', $uuid)->update(['active' => false]);

            $this->servicePlan->where('uuid', $uuid)->first()->inactivateAudit('Planos');

            return ['status' => true, 'message' => 'O Plano foi inativado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Plano não foi inativado.', 'error' => $th->getMessage()];
        }
    }

    public function destroy(string $uuid)
    {
        try {
            $this->servicePlan->where('uuid', $uuid)->delete();

            return ['status'=>true, 'message'=> 'O Plano foi excluído.'];

        } catch (\Throwable $th) {

            return ['status'=>false, 'message'=> 'O Plano não foi excluído', 'error'=> $th->getMessage()];
        }
    }

    public function planPermissions(string $uuid)
    {
        try {
            $plan = Plan::where('uuid', $uuid)->first();
            $plansId = $plan->permissions()->pluck('id');

            $permissionsPlan = $plan->permissions()->get();

            if (Auth::user()->isSuperAdmin()) {
                $notPermissionsPlan = Permission::whereNotIn('id', $plansId)->get();
            }

            return ['status'=>true, 'permissionsPlan'=> $permissionsPlan, 'notPermissionsPlan' => $notPermissionsPlan];

        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'Não foi possível carregar as permissões', 'error'=> $th->getMessage()];
        }
    }

    public function attachPermissions(array $data)
    {
        try {
            $plan = $this->servicePlan->where('uuid', $data['uuid'])->first();

            $plan->permissions()->attach($data['permissions']);

            return ['status'=>true, 'message'=> 'Permissões adicionadas.'];

        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'As permissões não foi adicionadas.', 'error'=> $th->getMessage()];
        }

    }

    public function detachPermissions(array $data)
    {
        try {
            $plan = $this->servicePlan->where('uuid', $data['uuid'])->first();

            $plan->permissions()->detach($data['permissions']);

            return ['status'=>true, 'message'=> 'Permissões excluidas.'];

        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'As Permissões não foi excluidas.', 'error'=> $th->getMessage()];
        }

    }
}
