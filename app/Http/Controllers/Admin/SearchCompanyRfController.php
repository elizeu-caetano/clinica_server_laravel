<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\Contracts\SearchCompanyRfRepositoryInterface;

class SearchCompanyRfController extends Controller
{
    private $repository;

    public function __construct(SearchCompanyRfRepositoryInterface $repository)
    {
       $this->repository = $repository;
    }

    public function getCompanyByCnpj($cnpj)
    {
        return $cnpj;
        return $this->repository->getCompanyByCnpj($cnpj);
    }
}
