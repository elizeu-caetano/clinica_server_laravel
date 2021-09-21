<?php

namespace App\Services\Admin;

use App\Repositories\Admin\Contracts\ProcedureRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\support\Str;

class ProcedureService
{
    protected $procedureRepository;

    public function __construct(ProcedureRepositoryInterface $procedureRepository)
    {
        $this->procedureRepository = $procedureRepository;
    }

    public function search(object $request)
    {
        $data = $request->all();
        $data['active'] = !$request->active ? false : true;

        return $this->procedureRepository->search($data);
    }

    public function store(object $request)
    {
        $data = $request->all();
        $data['uuid'] = Str::uuid();
        $data['contractor_id'] = Auth::user()->contractor_id;

        $procedure = $this->procedureRepository->store($data);

        if ($procedure['status']) {
            $discountTablesProcedure = $this->procedureRepository->storeDiscountTablesProcedure($procedure['data']->id);
            if (!$discountTablesProcedure['status']) {
                $procedure['data']->delete();
                return  $discountTablesProcedure;
            }
        }

        return $procedure;
    }

    public function show(string $uuid)
    {
        return $this->procedureRepository->show($uuid);
    }

    public function update(object $request)
    {
        $data = $request->except(['created_at', 'procedure_group', 'contractor']);

        return $this->procedureRepository->update($data);
    }

    public function activate(string $uuid)
    {
        return $this->procedureRepository->activate($uuid);
    }

    public function inactivate(string $uuid)
    {
        return $this->procedureRepository->inactivate($uuid);
    }

    public function deleted(string $uuid)
    {
        return $this->procedureRepository->deleted($uuid);
    }

    public function recover(string $uuid)
    {
        return $this->procedureRepository->show($uuid);
    }

    public function destroy(string $uuid)
    {
        return $this->procedureRepository->destroy($uuid);
    }
}
