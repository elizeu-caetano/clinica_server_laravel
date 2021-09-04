<?php

namespace App\Repositories\Admin;

use App\Http\Resources\Admin\DiscountTableResource;
use App\Models\Admin\DiscountTable;
use App\Repositories\Admin\Contracts\DiscountTableRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class DiscountTableRepository implements DiscountTableRepositoryInterface
{
    private $repository;

    public function __construct(DiscountTable $discountTable)
    {
        $this->repository = $discountTable;
    }

    public function search(array $data)
    {
        try {
            $discountTable = $this->repository->where('active', $data['active'])
                        ->where('deleted', false)
                        ->where(function ($query) {
                            if (Auth::user()->contractor_id != 1) {
                                $query->where('contractor_id', Auth::user()->contractor_id);
                            }
                        })
                        ->where('name', 'LIKE', '%'.$data['search'].'%')
                        ->orderBy('contractor_id')
                        ->orderBy('name');

            return ['status' => true, 'data' => DiscountTableResource::collection($discountTable->get())];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function store(array $data)
    {
        try {
            $discountTable = $this->repository->create($data);

            return ['status' => true, 'message' => 'A Tabela de Desconto foi cadastrada.', 'data' => new DiscountTableResource($discountTable)];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'A Tabela de Desconto não foi cadastrada.', 'error' => $th->getMessage()];
        }
    }

    public function show(string $uuid)
    {
        try {

            $discountTable = $this->repository->where('uuid', $uuid)->first();

            return ['status' => true, 'data' => new DiscountTableResource($discountTable)];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    public function update(array $data)
    {
        try {
            $discountTable = $this->repository->where('uuid', $data['uuid'])->first();
            $discountTable->update($data);

            return ['status' => true, 'data' => new DiscountTableResource($discountTable), 'message' => 'A Tabela de Desconto foi editada.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Tabela de Desconto não foi editada.', 'error' => $th->getMessage()];
        }
    }

    public function activate(string $uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['active' => true]);

            $this->repository->where('uuid', $uuid)->first()->activateAudit('Empresas');

            return ['status' => true, 'message' => 'A Tabela de Desconto foi ativada.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Tabela de Desconto não foi ativada.', 'error' => $th->getMessage()];
        }
    }

    public function inactivate(string $uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['active' => false]);

            $this->repository->where('uuid', $uuid)->first()->inactivateAudit('Empresas');

            return ['status' => true, 'message' => 'A Tabela de Desconto foi inativada.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Tabela de Desconto não foi inativada.', 'error' => $th->getMessage()];
        }
    }

    public function deleted(string $uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['deleted' => true]);

            $this->repository->where('uuid', $uuid)->first()->deletedAudit('Empresas');

            return ['status' => true, 'message' => 'A Tabela de Desconto foi deletada.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Tabela de Desconto não foi deletada.', 'error' => $th->getMessage()];
        }
    }

    public function recover(string $uuid)
    {
        try {
            $this->repository->where('uuid', $uuid)->update(['deleted' => false]);

            $this->repository->where('uuid', $uuid)->first()->recoverAudit('Empresas');

            return ['status' => true, 'message' => 'A Tabela de Desconto foi recuperada.'];
        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'A Tabela de Desconto não foi recuperada.', 'error' => $th->getMessage()];
        }
    }

    public function destroy(string $uuid)
    {
        try {
            $discountTable = $this->repository->where('uuid', $uuid)->first();

            $discountTable->delete();

            return ['status' => true, 'message' => 'A Tabela de Desconto foi excluída.'];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => 'A Tabela de Desconto não foi excluída.', 'error' => $th->getMessage()];
        }
    }
}
