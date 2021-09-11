<?php

namespace App\Repositories\JobMed\Contracts;

interface OccupationRepositoryInterface {

    public function search(array $data);

    public function store(array $data);

    public function show(int $id);

    public function update(array $data);

    public function activate(int $id);

    public function inactivate(int $id);

    public function deleted(int $id);

    public function recover(int $id);

    public function destroy(int $id);

    public function dangersOccupation(int $id);

    public function attachDangers(array $data);

    public function detachDangers(array $data);

    public function proceduresOccupation(int $id);

    public function attachProcedures(array $data);

    public function detachProcedures(array $data);
}
