<?php

namespace App\Models;

use App\Enums\TalkStatus;
use App\Enums\TalkType;
use Database\Factories\TalkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Talk extends Model
{
    /** @use HasFactory<TalkFactory> */
    use HasFactory;

    protected $casts = [
        'status' => TalkStatus::class,
        'type' => TalkType::class,
    ];

    /**
     * @return BelongsTo<Speaker, Talk> the speaker that is giving the talk
     */
    public function speaker(): BelongsTo
    {
        return $this->belongsTo(Speaker::class);
    }

    /**
     * @return BelongsTo<TalkCategory, Talk> the TalkCategory of the talk
     */
    public function talkCategory(): BelongsTo
    {
        return $this->belongsTo(TalkCategory::class);
    }

    /**
     * @return BelongsToMany<Conference>
     */
    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class, table: 'conference_talk')->withPivot('date_time');
    }

}
