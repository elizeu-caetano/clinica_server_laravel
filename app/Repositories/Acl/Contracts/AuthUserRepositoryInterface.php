<?php

namespace App\Repositories\Acl\Contracts;

interface AuthUserRepositoryInterface {

    public function auth(array $data, int $time);

    public function authorized();

    public function logout(array $data);

    public function emailConfirmation(string $uuid, string $token);
}
