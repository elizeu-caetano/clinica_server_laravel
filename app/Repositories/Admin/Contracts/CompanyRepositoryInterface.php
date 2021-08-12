<?php

namespace App\Repositories\Admin\Contracts;

interface CompanyRepositoryInterface {

    public function search($request);

    public function store($request);

    public function show($uuid);

    public function update($request);

    public function activate($uuid);

    public function inactivate($uuid);

    public function deleted($uuid);

    public function recover($uuid);

    public function destroy($uuid);

    public function companiesUser($uuid);
}
