<?php

namespace App\Repositories\Acl;

use App\Http\Resources\Acl\PlanResource;
use App\Models\Acl\Plan;
use App\Repositories\Acl\Contracts\PlanRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PlanRepository implements PlanRepositoryInterface {

    public function search($request)
    {
        try {

            $active = !$request->active ? false : true;
            $search = $request->search;

            $plans = Plan::where('active', $active)
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

            $plan = Plan::create($data);

            return ['status' => true, 'message' => 'O Plano foi cadastrado.', 'data' => new PlanResource($plan)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Plano não foi cadastrado.', 'error' => $th->getMessage()];
        }
    }

    public function show($uuid)
    {
        try {

            $plan = Plan::where('uuid', $uuid)->first();

            return ['status' => true, 'data' => new PlanResource($plan)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update($request)
    {
        try {

            $data = $request->except(['active', 'created_at']);

            $plan = Plan::where('uuid', $request->uuid)->first();
            $plan->update($data);

            return ['status' => true, 'data' => new PlanResource($plan), 'message' => 'O Plano foi editado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Plano não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function activate($uuid)
    {
        try {

            Plan::where('uuid', $uuid)->update(['active' => 1]);

            return ['status' => true, 'message' => 'O Plano foi ativado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Plano não foi ativado.', 'error' => $th->getMessage()];
        }
    }

    public function inactivate($uuid)
    {
        try {

            Plan::where('uuid', $uuid)->update(['active' => false]);

            return ['status' => true, 'message' => 'O Plano foi inativado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Plano não foi inativado.', 'error' => $th->getMessage()];
        }
    }

    public function destroy($uuid)
    {
        try {
            Plan::where('uuid', $uuid)->delete();

            return ['status'=>true, 'message'=> 'O Plano foi excluído.'];

        } catch (\Throwable $th) {

            return ['status'=>false, 'message'=> 'O Plano não foi excluído', 'error'=> $th->getMessage()];
        }
    }
}
