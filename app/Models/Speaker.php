<?php

namespace App\Models;

use AllowDynamicProperties;
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
     * @return HasMany<Talk> the talks given by this speaker that have been accepted
     */
    public function acceptedTalks(): HasMany
    {
        return $this->talks()->where('status', 'accepted');
    }

    /**
     * @return Attribute<Speaker, String> the full name of the speaker
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name . ' ' . $this->last_name,
        );
    }

    /**
     * @return Attribute<Speaker, String> the full name of the speaker with nickname
     */
    protected function fullNameWithNick() : Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name . ' ' . $this->last_name . ' (' . $this->nickname . ')',
        );
    }

    /**
     * @return Attribute<Speaker, Boolean> whether the speaker has accepted talks
     */
    protected function hasAcceptedTalks(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->acceptedTalks()->exists(),
        );
    }
}
