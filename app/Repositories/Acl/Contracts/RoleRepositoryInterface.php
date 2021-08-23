<?php

namespace App\Repositories\Acl\Contracts;

interface RoleRepositoryInterface {

    public function search(array $request);

    public function store(array $request);

    public function show(string $uuid);

    public function update(array $request);

    public function activate(string $uuid);

    public function inactivate(string $uuid);

    public function deleted(string $uuid);

    public function recover(string $uuid);

    public function destroy(string $uuid);

    public function rolePermissions(string $uuid);

    public function attachPermissions(array $request);

    public function detachPermissions(array $request);

}
