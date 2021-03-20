<?php

namespace App\Repositories\Acl;

use App\Http\Resources\Acl\ContractorResource;
use App\Models\Acl\Contractor;
use App\Repositories\Acl\Contracts\ContractorRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContractorRepository implements ContractorRepositoryInterface {

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

    public function search($request)
    {
       
        try {
                     
            $active = !$request->active ? false : true;
            $search = $request->search;

            $contractors = Contractor::where('active', $active)
            ->where('deleted', false)
            ->orWhere(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('fantasy_name', 'LIKE', "%{$search}%")
                ->orWhere('cpf_cnpj', 'LIKE', "%{$search}%");                
            })->get();

            return ['status' => true, 'data' => ContractorResource::collection($contractors)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function store($request)
    {
        try {

            $data = $request->all();

            if ($request->hasFile('logo') && $request->logo->isValid()) {
                $data['logo'] = $request->file('logo')->storePublicly('logos');
            }
                       
            
            $data['uuid'] = Str::uuid();
            $contractor = Contractor::create($data);

            return ['status' => true, 'message' => 'O Contratante foi cadastrado.', 'data' => new ContractorResource($contractor)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Contratante não foi cadastrado.', 'error' => $th->getMessage()];
        }
    }

    public function show($uuid)
    {
        try {
            $contractors = Contractor::where('uuid', $uuid)->with('plans')->first();
            return ['status' => true, 'data' => new ContractorResource($contractors)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update($request)
    {
        try {
            $request->all();
            $data = $request->except(['plan_id', 'person', 'active', 'deleted', 'created_at', 'logo']);
            $upadate = DB::table('contractors')->where('uuid', $request->uuid)->update($data);

            if ($upadate) {
                return ['status' => true, 'upadate' => true, 'message' => 'O Contratante foi editado.'];
            } else {
                return ['status' => true, 'upadate' => false, 'message' => 'Sem alterações para editar.'];
            }


        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Contratante não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function activate($uuid)
    {
        try {
            
            $upadate = DB::table('contractors')->where('uuid', $uuid)->update(['active' => 1]);

            if ($upadate) {
                return ['status' => true, 'upadate' => true, 'message' => 'O Contratante foi ativado.'];
            } else {
                return ['status' => true, 'upadate' => false, 'message' => 'Sem alterações para ativar.'];
            }


        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Contratante não foi ativado.', 'error' => $th->getMessage()];
        }
    }

    public function inactivate($uuid)
    {
        try {
            
            $upadate = DB::table('contractors')->where('uuid', $uuid)->update(['active' => false]);

            if ($upadate) {
                return ['status' => true, 'upadate' => true, 'message' => 'O Contratante foi inativado.'];
            } else {
                return ['status' => true, 'upadate' => false, 'message' => 'Sem alterações para inativar.'];
            }


        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Contratante não foi inativado.', 'error' => $th->getMessage()];
        }
    }
  
    public function destroy($uuid)
    {
        try {
            $destroy = Contractor::where('uuid', $uuid)->first();
            if ($destroy->count()) {
                $destroy->delete();
                return ['status'=>true, 'message'=> 'O Contratante foi excluído.'];
            }
        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'O Contratante não foi excluído', 'error'=> $th->getMessage()];
        }
    }
}