<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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
        foreach ($this->roles as $role => $wikiRole) {
            Role::updateOrCreate([
                'name' => $role,
            ], [
                'wiki_name' => $wikiRole,
            ]);
        }
    }
}
