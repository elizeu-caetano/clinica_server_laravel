<?php

namespace App\Repositories\Acl\Contracts;

interface PermissionRepositoryInterface {

    public function search($request);

    public function store($request);

    public function show($uuid);
    
    public function update($request);

    public function destroy($uuid);
    
}