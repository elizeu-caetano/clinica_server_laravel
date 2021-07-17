<?php

namespace App\Repositories\Acl;

use App\Http\Resources\Acl\ContractorResource;
use App\Http\Resources\Acl\PlanResource;
use App\Models\Acl\Contractor;
use App\Models\Acl\Plan;
use App\Repositories\Acl\Contracts\ContractorRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;
use Symfony\Component\Console\Input\Input;

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
            $data['plan_ids'] = explode(',', $request->plan_ids);
           // return $data;

            if ($request->hasFile('logo') && $request->logo->isValid()) {
                $data['logo'] = $request->file('logo')->storePublicly('logos', 's3');
            }

            $data['uuid'] = Str::uuid();
            $contractor = Contractor::create($data);
            $contractor->plans()->attach($data['plan_ids']);

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
            $contractor = Contractor::where('uuid', $uuid)->first();

            if ($contractor->logo != '') {
                Storage::disk('s3')->delete($contractor->logo);
            }

            $contractor->plans()->detach();

            $contractor->delete();

            return ['status'=> true, 'message'=> 'O Contratante foi excluído.'];

        } catch (\Throwable $th) {

            return ['status'=> false, 'message'=> 'O Contratante não foi excluído', 'error'=> $th->getMessage()];
        }
    }

    public function uploadLogo($request)
    {
        try {
            $contractor = Contractor::where('uuid', $request->uuid)->first();
            $extension = request()->file('image')->getClientOriginalExtension();
            $image = Image::make(request()->file('image'))->resize(500,315)->encode($extension);
            $path = 'logos/'.Str::random(40).'.'.$extension;
            Storage::disk('s3')->put($path, (string)$image, 'public');

            $contractor->update(['logo' => $path]);

            return ['status' => true, 'message' => 'A logo foi adicionada.'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A logo não foi adicionada.', 'error' => $th->getMessage()];
        }
    }

    public function contractorPlans($uuid)
    {
        try {
            $contractor = Contractor::where('uuid', $uuid)->first();
            $contractorsId = $contractor->plans()->pluck('id');

            $plansContractor = $contractor->plans()->get();

            $notPlansContractor = Plan::whereNotIn('id', $contractorsId)->get();

            return ['status'=>true, 'plansContractor'=> PlanResource::collection($plansContractor), 'notPlansContractor' => PlanResource::collection($notPlansContractor)];

        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'Não foi possível carregar os planos', 'error'=> $th->getMessage()];
        }
    }

    public function attachPlans($request)
    {
        try {
            $contractor = Contractor::where('uuid', $request->uuid)->first();

            $contractor->plans()->attach($request->plans);

            return ['status'=>true, 'message'=> 'Planos adicionados.'];

        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'Os planos não foram adicionados.', 'error'=> $th->getMessage()];
        }

    }

    public function detachPlans($request)
    {
        try {
            $contractor = Contractor::where('uuid', $request->uuid)->first();

            $contractor->plans()->detach($request->plans);

            return ['status'=>true, 'message'=> 'Planos excluidos.'];

        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'As Planos não foram excluidos.', 'error'=> $th->getMessage()];
        }

    }
}
