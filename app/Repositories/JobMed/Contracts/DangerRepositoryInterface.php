<?php

namespace App\Repositories\JobMed\Contracts;

interface DangerRepositoryInterface
{
    public function search(array $data);

    public function store(array $data);

    public function show(int $id);

    public function update(array $data);

    public function activate(int $id);

    public function inactivate(int $id);

    public function deleted(int $id);

    public function recover(int $id);

    public function destroy(int $id);
}
