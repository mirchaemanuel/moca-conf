<?php

namespace App\Models;

use Database\Factories\SpeakerFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    /**
     * @return Attribute<Speaker, String> the full name of the speaker
     */
    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name . ' ' . $this->last_name,
        );
    }
}
