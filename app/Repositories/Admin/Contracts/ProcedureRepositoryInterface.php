<?php

namespace App\Repositories\Admin\Contracts;

interface ProcedureRepositoryInterface {

    public function search(array $request);

    public function store(array $request);

    public function show(string $uuid);

    public function update(array $request);

    public function deleted(string $uuid);

    public function recover(string $uuid);

    public function activate(string $uuid);

    public function inactivate(string $uuid);

    public function destroy(string $uuid);
}
