<?php

namespace App\Models;

use App\Enums\ConferenceStatus;
use Database\Factories\ConferenceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Conference extends Model
{
    /** @use HasFactory<ConferenceFactory> */
    use HasFactory;

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => ConferenceStatus::class,
    ];

    /**
     * @return BelongsTo<Venue, Conference>
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * @return BelongsToMany<Talk>
     */
    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class, table: 'conference_talk')->withPivot('date_time');
    }
}
