<?php

namespace App\Repositories\Acl\Contracts;

interface PermissionRepositoryInterface {

    public function search($request);

    public function store($request);

    public function show($id);
    
    public function update($request);

    public function destroy($id);
    
}