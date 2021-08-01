<?php

namespace App\Repositories\Admin\Contracts;

interface SearchCompanyRfRepositoryInterface {

    public function getCompanyByCnpj($request);

}
