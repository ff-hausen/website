<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->string('wiki_name')->nullable()->after('name');
            $table->boolean('show_in_userlist')->default(false)->after('wiki_name');
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('wiki_name');
            $table->dropColumn('show_in_userlist');
        });
    }
};
