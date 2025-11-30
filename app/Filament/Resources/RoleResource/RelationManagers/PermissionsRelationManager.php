<?php

namespace App\Filament\Resources\RoleResource\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'permissions';

    protected static ?string $title = 'Berechtigungen';

    protected static ?string $modelLabel = 'Berechtigung';

    protected static ?string $pluralModelLabel = 'Berechtigungen';

    protected static ?string $recordTitleAttribute = 'name';

    public array $tableColumnSearches = ['name'];

    public function table(Table $table): Table
    {
        return $table
            ->searchable(['name'])
            ->columns([
                TextColumn::make('name'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->multiple()
                    ->preloadRecordSelect()
                    ->authorize('attach-any')
                    ->after(fn (PermissionRegistrar $registrar) => $registrar->forgetCachedPermissions()),
            ])
            ->recordActions([
                DetachAction::make()
                    ->authorize('detach')
                    ->after(fn (PermissionRegistrar $registrar) => $registrar->forgetCachedPermissions()),
            ])
            ->toolbarActions([
                DetachBulkAction::make()
                    ->authorize('detach-any')
                    ->after(fn (PermissionRegistrar $registrar) => $registrar->forgetCachedPermissions()),
            ]);
    }
}
