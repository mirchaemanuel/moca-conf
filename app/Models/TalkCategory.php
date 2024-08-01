<?php

namespace App\Models;

use Database\Factories\TalkCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TalkCategory extends Model
{
    /** @use HasFactory<TalkCategoryFactory> */
    use HasFactory;

    /**
     * @return HasMany<Talk> the talks in this TalkCategory
     */
    public function talks(): HasMany
    {
        return $this->hasMany(Talk::class);
    }
}
