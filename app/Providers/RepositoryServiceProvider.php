<?php

namespace App\Providers;

use App\Repositories\Acl\Contracts\{
    AuthUserRepositoryInterface,
    ContractorRepositoryInterface,
    PermissionRepositoryInterface,
    PlanRepositoryInterface,
    RoleRepositoryInterface,
    UserRepositoryInterface
};

use App\Repositories\Acl\{
    AuthUserRepository,
    ContractorRepository,
    PermissionRepository,
    PlanRepository,
    RoleRepository,
    UserRepository
};

use App\Repositories\Admin\{
    CompanyRepository,
    ProcedureGroupRepository
};

use App\Repositories\Admin\Contracts\{
    CompanyRepositoryInterface,
    ProcedureGroupRepositoryInterface
};

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AuthUserRepositoryInterface::class, AuthUserRepository::class);
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
        $this->app->bind(ContractorRepositoryInterface::class, ContractorRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(ProcedureGroupRepositoryInterface::class, ProcedureGroupRepository::class);
        $this->app->bind(PlanRepositoryInterface::class, PlanRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}
