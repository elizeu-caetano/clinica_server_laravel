<?php

namespace App\Repositories\Acl\Contracts;

interface UserRepositoryInterface {

    public function search($request);

    public function store($request);

    public function storeAdmin($request);

    public function show($uuid);

    public function update($request);

    public function activate($uuid);

    public function inactivate($uuid);

    public function deleted($uuid);

    public function recover($uuid);

    public function destroy($uuid);

    public function profile();

    public function profileUpdate($request);

    public function uploadPhotoProfile($request);

    public function updatePassword($request);
}
