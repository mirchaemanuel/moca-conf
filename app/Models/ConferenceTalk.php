<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ConferenceTalk extends Pivot
{
    public $incrementing = true;

    public $casts = [
        'date_time' => 'datetime',
    ];
}
