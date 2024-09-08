<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->timestamps();
        });

        foreach (\App\Models\RoleName::cases() as $role) {
            \App\Models\Role::create([
                'name' => $role->value,
            ]);
        }

        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Role::class);
            $table->foreignIdFor(\App\Models\User::class);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_user');
    }
};
