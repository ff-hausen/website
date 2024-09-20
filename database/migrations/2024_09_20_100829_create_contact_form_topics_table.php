<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_form_topics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->json('to');
            $table->json('cc')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_form_topics');
    }
};
