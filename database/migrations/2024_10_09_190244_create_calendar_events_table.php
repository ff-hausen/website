<?php

use App\Models\Calendar\Type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->datetime('start_time');
            $table->datetime('end_time')->nullable();
            $table->boolean('all_day')->default(false);
            $table->string('department')->default('ea');
            $table->foreignIdFor(Type::class)->nullable()
                ->constrained('calendar_types')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
