<?php

namespace App\Models;

use Database\Factories\SpeakerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Speaker extends Model
{
    /** @use HasFactory<SpeakerFactory> */
    use HasFactory;

    /**
     * @return HasMany<Talk> the talks given by this speaker
     */
    public function talks(): HasMany
    {
        return $this->hasMany(Talk::class);
    }
}
