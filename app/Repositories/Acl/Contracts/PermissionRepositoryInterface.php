<?php

namespace App\Repositories\Acl\Contracts;

interface PermissionRepositoryInterface {

    public function search(array $request);

    public function store(array $data, array $plan_ids, bool $developer);

    public function storeBatch(array $permissions, string $name, string $name_permission, array $plan_ids, bool $developer);

    public function show(int $id);

    public function update(array $request);

    public function destroy(int $id);

}
