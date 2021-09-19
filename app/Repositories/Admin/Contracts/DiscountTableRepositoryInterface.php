<?php

namespace App\Repositories\Admin\Contracts;

interface DiscountTableRepositoryInterface {

    public function search(array $data);

    public function store(array $data);

    public function show(string $uuid);

    public function update(array $data);

    public function activate(string $uuid);

    public function inactivate(string $uuid);

    public function deleted(string $uuid);

    public function recover(string $uuid);

    public function destroy(string $uuid);

    public function storeProceduresDiscountTable(int $discountTableId, array $data);
}
