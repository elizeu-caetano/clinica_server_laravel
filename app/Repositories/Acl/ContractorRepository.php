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
            ->where(function ($query) use ($search) {
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
                // $data['logo'] = Storage::disk('s3')->put('logo',$request->file('logo'));
                $data['logo'] = $request->file('logo')->storePublicly('logos', 's3');
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
            $data = $request->except(['plan_id', 'person', 'active', 'deleted', 'created_at', 'logo']);
            $contractor = Contractor::where('uuid', $request->uuid)->first();
            $contractor->update($data);

            return ['status' => true, 'message' => 'O Contratante foi editado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Contratante não foi editado.', 'error' => $th->getMessage()];
        }
    }

    public function activate($uuid)
    {
        try {

            Contractor::where('uuid', $uuid)->update(['active' => 1]);

            return ['status' => true, 'message' => 'O Contratante foi ativado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Contratante não foi ativado.', 'error' => $th->getMessage()];
        }
    }

    public function inactivate($uuid)
    {
        try {

            Contractor::where('uuid', $uuid)->update(['active' => false]);

            return ['status' => true, 'message' => 'O Contratante foi inativado.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'O Contratante não foi inativado.', 'error' => $th->getMessage()];
        }
    }

    public function destroy($uuid)
    {
        try {
            Contractor::where('uuid', $uuid)->delete();

            return ['status'=>true, 'message'=> 'O Contratante foi excluído.'];

        } catch (\Throwable $th) {

            return ['status'=>false, 'message'=> 'O Contratante não foi excluído', 'error'=> $th->getMessage()];
        }
    }
}
