<?php

namespace App\Http\Controllers\Acl;
use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\AuthUserRequest;
use App\Repositories\Acl\Contracts\AuthUserRepositoryInterface;
use Illuminate\Http\Request;

class AuthUserController extends Controller
{
    private $repository;

    public function __construct(AuthUserRepositoryInterface $repository)
    {
       $this->repository = $repository;
    }

    public function auth(AuthUserRequest $request)
    {
        return $this->repository->auth($request);
    }

    public function authorized(){
        return $this->repository->authorized();
    }

    public function logout(Request $request)
    {
        return $this->repository->logout($request);
    }
}
