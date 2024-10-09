<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendar_responsibility', function (Blueprint $table) {
            $table->foreignIdFor(App\Models\Calendar\Event::class)
                ->constrained('calendar_events')->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class)
                ->constrained()->cascadeOnDelete();

            $table->timestamps();

            $table->primary(['event_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_responsibility');
    }
};
