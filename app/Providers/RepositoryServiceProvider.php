<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;

// Acl
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

// Adm
use App\Repositories\Admin\{
    AuditRepository,
    CompanyRepository,
    DiscountTableRepository,
    ProcedureRepository,
    ProcedureGroupRepository
};

use App\Repositories\Admin\Contracts\{
    AuditRepositoryInterface,
    CompanyRepositoryInterface,
    DiscountTableRepositoryInterface,
    ProcedureRepositoryInterface,
    ProcedureGroupRepositoryInterface
};

// JobMed
use App\Repositories\JobMed\Contracts\{
    OccupationRepositoryInterface
};

use App\Repositories\JobMed\{
    OccupationRepository
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AuditRepositoryInterface::class, AuditRepository::class);
        $this->app->bind(AuthUserRepositoryInterface::class, AuthUserRepository::class);
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
        $this->app->bind(ContractorRepositoryInterface::class, ContractorRepository::class);
        $this->app->bind(OccupationRepositoryInterface::class, OccupationRepository::class);
        $this->app->bind(DiscountTableRepositoryInterface::class, DiscountTableRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(ProcedureRepositoryInterface::class, ProcedureRepository::class);
        $this->app->bind(ProcedureGroupRepositoryInterface::class, ProcedureGroupRepository::class);
        $this->app->bind(PlanRepositoryInterface::class, PlanRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}
