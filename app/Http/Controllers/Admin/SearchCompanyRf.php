<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\Contracts\SearchCompanyRfRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SearchCompanyRf extends Controller
{
    private $repository;

    public function __construct(SearchCompanyRfRepositoryInterface $repository)
    {
       $this->repository = $repository;
    }

    public function getCompanyByCnpj($cnpj)
    {
        //Gate::authorize('search_company');

        return $this->repository->getCompanyByCnpj($cnpj);
    }
}
