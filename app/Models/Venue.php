<?php

namespace App\Models;

use Database\Factories\VenueFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    /** @use HasFactory<VenueFactory> */
    use HasFactory;

    /**
     * @return HasMany<Conference> the conferences held at this venue
     */
    public function conferences(): HasMany
    {
        return $this->hasMany(Conference::class);
    }
}
