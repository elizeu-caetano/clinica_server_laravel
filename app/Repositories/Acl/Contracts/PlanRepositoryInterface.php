<?php

namespace App\Repositories\Acl\Contracts;

interface PlanRepositoryInterface {

    public function search(array $request);

    public function store(array $request);

    public function show(string $uuid);

    public function update(array $request);

    public function activate(string $uuid);

    public function inactivate(string $uuid);

    public function destroy(string $uuid);

    public function planPermissions(string $uuid);

    public function attachPermissions(array $request);

    public function detachPermissions(array $request);
}
