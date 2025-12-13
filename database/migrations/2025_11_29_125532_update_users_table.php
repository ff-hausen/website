<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->dropColumn('name');

            $table->datetime('user_approved_at')->nullable()->after('email_verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('last_name');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');

            $table->dropColumn('user_approved_at');
        });
    }
};
