<?php

namespace App\Repositories\Admin\Contracts;

interface AuditRepositoryInterface {

    public function search(array $request);

    public function show(int $id);
}
