<?php

namespace App\Repositories\Admin\Contracts;

interface ProcedureGroupRepositoryInterface {

    public function search(array $request);

    public function store(array $request);

    public function show(string $uuid);

    public function update(array $request);

    public function deleted(string $uuid);

    public function recover(string $uuid);

    public function destroy(string $uuid);
}
