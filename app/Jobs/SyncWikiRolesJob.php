<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Connection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SyncWikiRolesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // 'Wiki Role Name' => ['Website Role Name', ...]
    public static array $roleMap = [
        'Jugendfeuerwehr' => ['Jugendfeuerwehr Leitung', 'Jugendfeuerwehr Betreuer'],
        'Wehrführung' => 'Wehrführung',
        'Vorstand' => 'Vereinsvorstand',
        'Einsatzabteilung' => 'Einsatzabteilung',
        'Gruppenführer' => 'Gruppenführer',
        'Minifeuerwehr' => ['Minifeuerwehr Leitung', 'Minifeuerwehr Betreuer'],
        'Verein' => 'Vereinsmitglied',
    ];

    protected Connection $wiki;

    public function __construct(protected $dryRun = true) {}

    public function handle(): void
    {
        $roles = $this->getRoles(); // Map from wiki rolename to wiki roleid

        foreach ($roles as $roleId => $roleName) {
            $webRoles = Arr::wrap(self::$roleMap[$roleName] ?? null); // List of Web Rolenames i.e. ['Jugendfeuerwehr Leitung', 'Jugendfeuerwehr Betreuer']

            if (! $webRoles) {
                continue;
            }

            // get web users in the corresponding web roles
            $webUsers = $this->getUsersInWebRoles($webRoles);

            // get wiki users in the corresponding wiki role
            $wikiUsers = $this->getUsersInWikiRole($roleId);

            // get wiki users to remove
            $usersToRemove = $wikiUsers->diff($webUsers);

            // get wiki users to add
            $usersToAdd = $webUsers->diff($wikiUsers);

            // Remove roles
            $this->revokeRole($roleId, $usersToRemove);
            $this->giveRole($roleId, $usersToAdd);
        }
    }

    protected function revokeRole(int $roleId, Collection $usersToRemove)
    {
        if ($this->dryRun) {
            dump("Would remove role $roleId from following users: ".$usersToRemove->join(', '));

            return;
        }

        DB::connection('wiki')->table('role_user')
            ->where('role_id', $roleId)
            ->whereIn('user_id', $usersToRemove)
            ->delete();
    }

    protected function giveRole(int $roleId, Collection $usersToAdd)
    {
        $insertData = $usersToAdd->map(fn ($userId) => ['role_id' => $roleId, 'user_id' => $userId]);

        if ($this->dryRun) {
            dump("Would add role $roleId to following users: ".$usersToAdd->join(', '));

            return;
        }

        DB::connection('wiki')->table('role_user')
            ->insert($insertData->values()->toArray());
    }

    protected function getRoles()
    {
        return DB::connection('wiki')
            ->table('roles')->get(['id', 'display_name'])
            ->mapWithKeys(fn ($item) => [$item->id => $item->display_name]);
    }

    /**
     * @return Collection<int>
     */
    public function getUsersInWebRoles(array $webRoles): Collection
    {
        $userIdMap = $this->getUserIdMap(); // Map from web userid to wiki userid

        return User::whereHas('roles', function ($query) use ($webRoles) {
            $query->whereIn('name', $webRoles);
        })->get(['id'])
            ->pluck('id')
            // filter users for existing wiki users
            ->filter(fn ($id) => isset($userIdMap[$id]))
            ->map(fn ($id) => $userIdMap[$id]);
    }

    protected function getUserIdMap()
    {
        return DB::connection('wiki')
            ->table('social_accounts')
            ->where('driver', 'ffhausen')
            ->get(['user_id', 'driver_id'])
            ->mapWithKeys(fn ($item) => [$item->driver_id => $item->user_id]);
    }

    public function getUsersInWikiRole($roleId): Collection
    {
        return DB::connection('wiki')->table('role_user')
            ->where('role_id', $roleId)
            ->get(['user_id'])
            ->pluck('user_id');
    }
}
