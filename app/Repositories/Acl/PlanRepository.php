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
            ->orWhere(function ($query) use ($search) {
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

            $user = Plan::where('uuid', $uuid)->first();

            return ['status' => true, 'data' => new PlanResource($user)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update($request)
    {
        try {      

            $data = $request->all();
           
            $update = DB::table('plans')->where('uuid', $request->uuid)->update($data);

            if ($update) {
                return ['status' => true, 'upadate' => true, 'message' => 'O Plano foi editado.'];
            } else {
                return ['status' => true, 'upadate' => false, 'message' => 'Sem alterações para editar.'];
            }

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Plano não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function activate($uuid)
    {
        try {
            
            $update = DB::table('plans')->where('uuid', $uuid)->update(['active' => 1]);

            if ($update) {
                return ['status' => true, 'upadate' => true, 'message' => 'O Plano foi ativado.'];
            } else {
                return ['status' => true, 'upadate' => false, 'message' => 'Sem alterações para ativar.'];
            }


        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Plano não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function inactivate($uuid)
    {
        try {

            $update = DB::table('plans')->where('uuid', $uuid)->update(['active' => false]);

            if ($update) {
                return ['status' => true, 'upadate' => true, 'message' => 'O Plano foi inativado.'];
            } else {
                return ['status' => true, 'upadate' => false, 'message' => 'Sem alterações para inativar.'];
            }


        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Plano não foi editado.', 'error' => $th->getMessage()];
        }
    }
    
    public function destroy($uuid)
    {
        try {
            $destroy = Plan::where('uuid', $uuid)->first();
          
            $destroy->delete();
            
            return ['status'=>true, 'message'=> 'O Plano foi excluído.'];

        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'O Plano não foi excluído', 'error'=> $th->getMessage()];
        }
    }
}