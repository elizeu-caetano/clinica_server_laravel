<?php

namespace App\Services\Acl;

use App\Models\Acl\Contractor;
use App\Repositories\Acl\Contracts\ContractorRepositoryInterface;
use Illuminate\support\Str;
use Illuminate\Support\Facades\Storage;
use Image;

class ContractorService
{
    protected $contractorService;

    public function __construct(ContractorRepositoryInterface $contractorService)
    {
        $this->contractorService = $contractorService;
    }

    public function index()
    {
        return $this->contractorService->index();
    }

    public function search(object $request)
    {
        $data = $request->all();
        $data['active'] = !$request->active ? false : true;

        return $this->contractorService->search($data);
    }

    public function store(object $request)
    {

        $data = $request->all();

        if ($request->hasFile('logo') && $request->logo->isValid()) {
            $data['logo'] = $request->file('logo')->storePublicly('logos', 's3');
        }

        $data['plan_ids'] = explode(',', $request->plan_ids);
        $data['uuid'] = Str::uuid();

        return $this->contractorService->store($data);
    }

    public function show(string $uuid)
    {
        return $this->contractorService->show($uuid);
    }

    public function update(object $request)
    {
        $data = $request->except(['plan_id', 'person', 'active', 'deleted', 'created_at', 'logo']);

        return $this->contractorService->update($data);
    }

    public function activate(string $uuid)
    {
        return $this->contractorService->activate($uuid);
    }

    public function inactivate(string $uuid)
    {
        return $this->contractorService->inactivate($uuid);
    }

    public function destroy(string $uuid)
    {
        return $this->contractorService->destroy($uuid);
    }

    public function uploadLogo(object $request)
    {
        $extension = request()->file('image')->getClientOriginalExtension();
        $image = Image::make(request()->file('image'))->resize(500, 315)->encode($extension);
        $path = 'logos/' . Str::random(40) . '.' . $extension;
        Storage::disk('s3')->put($path, (string)$image, 'public');

        $contractor = Contractor::where('uuid', $request->uuid)->first();

        if ($contractor->logo != '') {
            Storage::disk('s3')->delete($contractor->logo);
        }

        return $this->contractorService->uploadLogo($contractor, $path);
    }

    public function contractorPlans(string $uuid)
    {
        return $this->contractorService->contractorPlans($uuid);
    }

    public function attachPlans(object $request)
    {
        $data = $request->all();
        return $this->contractorService->attachPlans($data);
    }

    public function detachPlans(object $request)
    {
        $data = $request->all();
        return $this->contractorService->detachPlans($data);
    }
}
