<?php

use App\Models\Conference;
use App\Models\Talk;
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
        Schema::create('conference_talk', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Conference::class)->constrained(); // belongsTo Conference
            $table->foreignIdFor(Talk::class)->constrained(); // belongsTo Talk

            // add day and hour for the talk program
            $table->dateTime('date_time');

            $table->timestamps();

            $table->unique(['conference_id', 'talk_id'], 'conference_talk_unique_idx');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conference_talk');
    }
};
