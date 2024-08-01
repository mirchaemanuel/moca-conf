<?php

use App\Enums\TalkStatus;
use App\Enums\TalkType;
use App\Models\Speaker;
use App\Models\TalkCategory;
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
        Schema::create('talks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Speaker::class)->constrained(); // belongsTo Speaker
            $table->foreignIdFor(TalkCategory::class)->constrained(); // hasOne TalkCategory

            $table->string('title');
            $table->text('abstract');
            $table->text('description');

            $table->enum('type', array_map(fn ($case) => $case->value, TalkType::cases()))->default(TalkType::Keynote);
            $table->integer('duration')->comment('Duration in minutes')->default(30);

            $table->enum('status', array_map(fn ($case) => $case->value, TalkStatus::cases()))->default(TalkStatus::Submitted);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talks');
    }
};
