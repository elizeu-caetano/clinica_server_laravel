<?php

namespace App\Repositories\Acl\Contracts;

interface AuthUserRepositoryInterface {

    public function auth($request);

    public function authorized();

    public function logout($request);

    public function emailConfirmation($uuid, $token);
}
