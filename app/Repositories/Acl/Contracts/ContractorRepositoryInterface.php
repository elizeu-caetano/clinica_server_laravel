<?php

namespace App\Repositories\Acl\Contracts;

interface ContractorRepositoryInterface {

    public function index();
    
    public function search($request);

    public function store($request);

    public function show($uuid);
    
    public function update($request);

    public function activate($uuid);

    public function inactivate($uuid);

    public function destroy($uuid);
    
}