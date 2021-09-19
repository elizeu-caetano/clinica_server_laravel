<?php

namespace App\Services\Admin;

use App\Models\Admin\Procedure;
use App\Repositories\Admin\Contracts\DiscountTableRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\support\Str;

class DiscountTableService
{
    protected $repository;

    public function __construct(DiscountTableRepositoryInterface $interface)
    {
        $this->repository = $interface;
    }

    public function search(object $request)
    {
        $data = $request->all();
        $data['active'] = !$request->active ? false : true;

        return $this->repository->search($data);
    }

    public function store(object $request)
    {

        $data = $request->all();
        $data['contractor_id'] = $request->contractor_id ? $request->contractor_id : Auth::user()->contractor_id;
        $data['uuid'] = Str::uuid();

        $discountTable = $this->repository->store($data);

        if ($discountTable['status']) {
            $procedures = Procedure::where('contractor_id', Auth::user()->contractor_id)->get();

            $data = [];
            foreach ($procedures as $procedure) {
                $data[$procedure->id] = [
                    'price' => $procedure->price,
                ];
            }

            $proceduresDiscountTable = $this->repository->storeProceduresDiscountTable($discountTable['data']->id, $data);
            if (!$proceduresDiscountTable['status']) {
                $discountTable['data']->delete();
                return $proceduresDiscountTable;
            }
        }

        return $discountTable;
    }

    public function show(string $uuid)
    {
        return $this->repository->show($uuid);
    }

    public function update(object $request)
    {
        $data = $request->except(['created_at']);

        return $this->repository->update($data);
    }

    public function activate(string $uuid)
    {
        return $this->repository->activate($uuid);
    }

    public function inactivate(string $uuid)
    {
        return $this->repository->inactivate($uuid);
    }

    public function deleted(string $uuid)
    {
        return $this->repository->deleted($uuid);
    }

    public function recover(string $uuid)
    {
        return $this->repository->show($uuid);
    }

    public function destroy(string $uuid)
    {
        return $this->repository->destroy($uuid);
    }
}
