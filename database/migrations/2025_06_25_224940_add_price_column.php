<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ausflug_participants', function (Blueprint $table) {
            $table->unsignedInteger('price')->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('ausflug_participants', function (Blueprint $table) {
            $table->removeColumn('price');
        });
    }
};
