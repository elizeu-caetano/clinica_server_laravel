<?php

namespace App\Repositories\Acl\Contracts;

interface ContractorRepositoryInterface {

    public function index();

    public function search(array $request);

    public function store(array $request);

    public function show(string $uuid);

    public function update(array $request);

    public function activate(string $uuid);

    public function inactivate(string $uuid);

    public function destroy(string $uuid);

    public function uploadLogo(object $contractor, string $path);

    public function contractorPlans(string $uuid);

    public function attachPlans(array $request);

    public function detachPlans(array $request);

}
