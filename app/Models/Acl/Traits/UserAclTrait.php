<?php

namespace App\Models\Acl\Traits;

use App\Models\Acl\Permission;
use App\Models\Acl\Role;
use Illuminate\Support\Facades\DB;

trait UserAclTrait {

    public function permissions()
    {
        if ($this->isSuperAdmin()) {
            $permissions = DB::table('permissions')->pluck('permission')->toArray();
            return array_unique($permissions);
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
        $idRoles = $this->roles->pluck('id')->toArray();

        $idPermissionsRoles = DB::table('permission_role')->whereIn('role_id', $idRoles)->pluck('permission_id');

        $permissions = Permission::whereIn('id', $idPermissionsRoles)->pluck('permission')->toArray();

        return array_unique($permissions);
    }

    private function permissionsPlan()
    {
        $idPlans = $this->contractor->plans->pluck('id');

        $idPermissionsPlans = DB::table('permission_plan')->whereIn('plan_id', $idPlans)->pluck('permission_id');

        $permissions = Permission::whereIn('id', $idPermissionsPlans)->pluck('permission')->toArray();

        return array_unique($permissions);
    }
}
