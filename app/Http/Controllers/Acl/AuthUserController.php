<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\AuthUserRequest;
use App\Services\Acl\AuthUserService;
use Illuminate\Http\Request;

class AuthUserController extends Controller
{
    private $authUserService;

    public function __construct(AuthUserService $authUserService)
    {
        $this->authUserService = $authUserService;
    }

    public function auth(AuthUserRequest $request)
    {
        return $this->authUserService->auth($request);
    }

    public function authorized()
    {
        return $this->authUserService->authorized();
    }

    public function logout(Request $request)
    {
        return $this->authUserService->logout($request);
    }

    public function emailConfirmation($uuid, $token)
    {
        return $this->authUserService->emailConfirmation($uuid, $token);

    }
}
