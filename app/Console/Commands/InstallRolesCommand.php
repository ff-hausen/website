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

    public function handle(): void
    {
        $this->createRoles();

        $this->createAdminPermissions();

        $this->createAdminUser();
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

    public function createAdminPermissions(): void
    {
        $adminPermission = Permission::createOrFirst([
            'name' => 'can access admin panel',
        ]);
        $adminPermission->assignRole('Administrator');
    }
}
