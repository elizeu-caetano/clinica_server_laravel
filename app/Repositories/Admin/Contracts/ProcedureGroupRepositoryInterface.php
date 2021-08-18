<?php

namespace App\Repositories\Admin\Contracts;

interface ProcedureGroupRepositoryInterface {

    public function search($request);

    public function store($request);

    public function show($uuid);

    public function update($request);

    public function deleted($uuid);

    public function recover($uuid);

    public function destroy($uuid);
}
