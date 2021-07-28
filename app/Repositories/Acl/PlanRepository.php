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

    private $repository;

    public function __construct(Plan $plan)
    {
        $this->repository = $plan;
    }

    public function search($request)
    {
        try {

            $active = !$request->active ? false : true;
            $search = $request->search;

            $plans = $this->repository->where('active', $active)
            ->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('price', 'LIKE', "%{$search}%");
            })->get();

            return ['status' => true, 'data' => PlanResource::collection($plans)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function store($request)
    {
        try {

            $data = $request->all();
            $data['uuid'] = Str::uuid();

            $plan = $this->repository->create($data);

            return ['status' => true, 'message' => 'O Plano foi cadastrado.', 'data' => new PlanResource($plan)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Plano não foi cadastrado.', 'error' => $th->getMessage()];
        }
    }

    public function show($uuid)
    {
        try {

            $plan = $this->repository->where('uuid', $uuid)->first();

            return ['status' => true, 'data' => new PlanResource($plan)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update($request)
    {
        try {

            $data = $request->except(['active', 'created_at']);

            $plan = $this->repository->where('uuid', $request->uuid)->first();
            $plan->update($data);

            return ['status' => true, 'data' => new PlanResource($plan), 'message' => 'O Plano foi editado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Plano não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function activate($uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['active' => true]);

            $this->repository->where('uuid', $uuid)->first()->activateAudit('Planos');

            return ['status' => true, 'message' => 'O Plano foi ativado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Plano não foi ativado.', 'error' => $th->getMessage()];
        }
    }

    public function inactivate($uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['active' => false]);

            $this->repository->where('uuid', $uuid)->first()->inactivateAudit('Planos');

            return ['status' => true, 'message' => 'O Plano foi inativado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Plano não foi inativado.', 'error' => $th->getMessage()];
        }
    }

    public function destroy($uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->delete();

            return ['status'=>true, 'message'=> 'O Plano foi excluído.'];

        } catch (\Throwable $th) {

            return ['status'=>false, 'message'=> 'O Plano não foi excluído', 'error'=> $th->getMessage()];
        }
    }

    public function planPermissions($uuid)
    {
        try {
            $plan = $this->repository->where('uuid', $uuid)->first();
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

    public function attachPermissions($request)
    {
        try {
            $plan = $this->repository->where('uuid', $request->uuid)->first();

            $plan->permissions()->attach($request->permissions);

            return ['status'=>true, 'message'=> 'Permissões adicionadas.'];

        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'As permissões não foi adicionadas.', 'error'=> $th->getMessage()];
        }

    }

    public function detachPermissions($request)
    {
        try {
            $plan = $this->repository->where('uuid', $request->uuid)->first();

            $plan->permissions()->detach($request->permissions);

            return ['status'=>true, 'message'=> 'Permissões excluidas.'];

        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'As Permissões não foi excluidas.', 'error'=> $th->getMessage()];
        }

    }
}
