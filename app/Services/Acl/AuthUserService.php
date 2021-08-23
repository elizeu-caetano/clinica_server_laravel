<?php

namespace App\Services\Acl;

use App\Repositories\Acl\Contracts\AuthUserRepositoryInterface;

class AuthUserService
{
    protected $authUserService;

    public function __construct(AuthUserRepositoryInterface $authUserService)
    {
        $this->authUserService = $authUserService;
    }

    public function auth(object $request)
    {
        $data = $request->all();

        //define how many hours for the token to expire
        $time = $request->timeToken ? $request->timeToken : 12;

        return $this->authUserService->auth($data, $time);
    }

    public function authorized()
    {
        return $this->authUserService->authorized();
    }

    public function logout(object $request)
    {
        $data = $request->all();
        return $this->authUserService->logout($data);
    }

    public function emailConfirmation(string $uuid, string $token)
    {
        return $this->authUserService->emailConfirmation($uuid, $token);
    }

}
