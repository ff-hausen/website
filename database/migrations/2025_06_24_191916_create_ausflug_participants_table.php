<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ausflug_participants', function (Blueprint $table) {
            $table->id();
            $table->uuid('submission_id');
            $table->string('name');
            $table->string('street');
            $table->string('zip_code');
            $table->string('city');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('type');
            $table->boolean('primary')->default(false);
            $table->boolean('verified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ausflug_participants');
    }
};
