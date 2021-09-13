<?php

namespace App\Repositories\JobMed\Contracts;

interface CompanyOccupationRepositoryInterface {

    public function index(int $id);

    public function search(int $company_id, string $search);

    public function store(array $data);

    public function show(int $id);

    public function update(array $data);

    public function destroy(string $id);
}
