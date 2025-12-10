<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InstallRolesCommand extends Command
{
    protected $signature = 'roles:install';

    protected $description = 'Install roles';

    protected $roles = [
        'Administrator' => 'Admin',
        'Wehrführung' => null,
        'Einsatzabteilung' => null,
        'Vereinsmitglied' => 'Verein',
        'Vereinsvorstand' => 'Vorstand',
        'Alters- und Ehrenabteilung' => null,
        'JF Leitung' => 'Jugendfeuerwehr',
        'JF Team' => 'Jugendfeuerwehr',
        'MF Leitung' => 'Minifeuerwehr',
        'MF Team' => 'Minifeuerwehr',
        'Gruppenführer' => null,
    ];

    protected $permissions = [
        // Admin
        'admin-panel:access',
        // User
        'users:view-any',
        'users:create',
        'users:update',
        'users:delete-any',
        // Role
        'roles:view-any',
        'roles:create',
        'roles:update',
        'roles:delete-any',
        // Permissions
        'permissions:view-any',
        'permissions:create',
        'permissions:update',
        'permissions:delete-any',
        'permissions:assign',
    ];

    public function handle(): void
    {
        $this->createRoles();

        $this->createPermissions();

        $this->createAdminUser();

        $this->assignPermissionsToAdmin();
    }

    public function createRoles(): void
    {
        foreach ($this->roles as $role => $wikiRole) {
            Role::updateOrCreate([
                'name' => $role,
            ], [
                'wiki_name' => $wikiRole,
            ]);
        }
    }

    public function assignPermissionsToAdmin(): void
    {
        $adminRole = Role::whereName('Administrator')->first();
        foreach ($this->permissions as $permission) {
            $adminRole->givePermissionTo($permission);
        }
    }

    public function createAdminUser(): void
    {
        if (User::count() === 0) {
            $adminUser = User::forceCreate([
                'first_name' => 'Admin',
                'email' => 'admin@ff-frankfurt-hausen.de',
                'email_verified_at' => now(),
                'user_verified_at' => now(),
                'password' => Hash::make('changeme'),
            ]);
            $adminUser->assignRole('Administrator');
        }
    }

    protected function createPermissions()
    {
        foreach ($this->permissions as $permission) {
            Permission::createOrFirst([
                'name' => $permission,
            ]);
        }
    }
}
