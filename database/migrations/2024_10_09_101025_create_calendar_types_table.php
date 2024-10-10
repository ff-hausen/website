<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendar_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('department')->default('ea');
            $table->string('background_color')->nullable();
            $table->string('text_color')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_types');
    }
};
