<?php

namespace App\Console\Commands\Roles;

use App\Models\Role;
use App\Models\RoleName;
use Illuminate\Console\Command;

class ImportCommand extends Command
{
    protected $signature = 'roles:import';

    protected $description = 'Imports roles';

    public function handle(): void
    {
        $imported = 0;
        foreach (RoleName::cases() as $item) {
            try {
                Role::create([
                    'name' => $item->value,
                ]);

                $imported++;
            } catch (\Exception $e) {
                //
            }
        }

        $this->info("Imported {$imported} roles.");
    }
}
