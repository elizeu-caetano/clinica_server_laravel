<?php

namespace App\Repositories\Admin\Contracts;

interface AuditRepositoryInterface {

    public function search($request);

    public function show($id);
}
