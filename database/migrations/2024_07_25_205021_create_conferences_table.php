<?php

use App\Enums\ConferenceStatus;
use App\Models\Venue;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conferences', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Venue::class)->constrained(); // belongsTo Venue

            $table->string('name');
            $table->string('slug');

            $table->text('description')->nullable();

            $table->dateTime('start_date');
            $table->dateTime('end_date');

            $table->enum('status', array_map(fn ($case) => $case->value, ConferenceStatus::cases()))->default(ConferenceStatus::Draft);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conferences');
    }
};
