<?php

namespace App\Models\Traits;

use App\Models\Acl\Permission;
use Illuminate\Support\Facades\DB;

trait UserAclTrait {

    public function permissions()
    {
        if ($this->isSuperAdmin()) {
           return Permission::pluck('permission')->toArray();
        } else if ($this->isAdmin()) {
            return $this->permissionsPlan();
        } else {
            return $this->permissionsUser();
        }

    }
        
    public function hasPermission(string $permission): bool
    {  
        return in_array($permission, $this->permissions());
    }

    public function isAdmin(): bool
    {
        foreach ($this->roles as  $role) {
            if($role->admin){
                return true;
            };
        }
        return false;
    }


    public function isSuperAdmin(): bool
    {        
        return in_array($this->email, config('Acl.superAdmin'));
    }

    private function permissionsUser()
    {
        $idRoles = $this->roles->pluck('id');
        $idPermissionsRoles = DB::table('permission_role')->whereIn('role_id', $idRoles)->pluck('permission_id');

        $idPlans = $this->contractor->plans->pluck('id');

        $permissions = DB::table('permissions as p')
        ->join('permission_plan as pp', 'pp.permission_id', '=', 'p.id')
        ->whereIn('pp.plan_id', $idPlans)
        ->whereIn('pp.permission_id', $idPermissionsRoles)
        ->pluck('p.permission')
        ->toArray();

        return array_unique($permissions);
    }

    private function permissionsPlan()
    {
        $idPlans = $this->contractor->plans->pluck('id');

        $permissions =  DB::table('permissions as p')
        ->join('permission_plan as pp', 'pp.permission_id', '=', 'p.id')
        ->whereIn('pp.plan_id', $idPlans)
        ->pluck('p.permission')
        ->toArray();

        return array_unique($permissions);
    }
}