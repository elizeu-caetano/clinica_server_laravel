<?php

namespace App\Observers;

use App\Events\NewUser;
use App\Models\Acl\{
    Contractor,
    Role,
    User
};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ContractorObserver
{
    /**
     * Handle the Contractor "created" event.
     *
     * @param  \App\Models\Acl\\Contractor  $contractor
     * @return void
     */
    public function created(Contractor $contractor)
    {
        $data['uuid'] = Str::uuid();
        $data['name'] = 'Administrador';
        $data['description'] = 'Administra o sistema da ' . Str::title($contractor->fantasy_name);
        $data['admin'] = true;
        $data['contractor_id'] = $contractor->id;
        $role = Role::create($data);

        $role->email = $contractor->email;
        $role->fantasy_name = $contractor->fantasy_name;
        $role->photo = $contractor->logo;
        $role->phone = $contractor->phone;

        $this->storeUser($role);
    }

    private function storeUser($role)
    {
        $senha = rand(111111, 999999);

        $data['uuid'] = Str::uuid();
        $data['name'] = $role->fantasy_name;
        $data['email'] = $role->email;
        $data['password'] = Hash::make($senha);
        $data['photo'] = $role->photo;
        $data['contractor_id'] = $role->contractor_id;
        $data['token'] = Str::random(40);
        $data['phone'] = $role->phone;
        $data['type'] = 'Telefone';

        $user = User::create($data);
        $user->roles()->attach($role->id);
        $user->phones()->create($data);

        $user->password = $senha;
        event(new NewUser($user));
    }
}
