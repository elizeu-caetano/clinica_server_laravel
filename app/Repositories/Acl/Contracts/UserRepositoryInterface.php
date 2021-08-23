<?php

namespace App\Repositories\Acl\Contracts;

interface UserRepositoryInterface {

    public function search(array $request);

    public function store(array $request, string $password);

    public function storeAdmin(array $request, string $password);

    public function show(string $uuid);

    public function update(array $request);

    public function activate(string $uuid);

    public function inactivate(string $uuid);

    public function deleted(string $uuid);

    public function recover(string $uuid);

    public function destroy(string $uuid);

    public function attachCompany(array $request);

    public function detachCompany(array $request);

    public function profile();

    public function profileUpdate(array $request);

    public function uploadPhotoProfile(object $user, string $path);

    public function updatePassword(string $password);
}
