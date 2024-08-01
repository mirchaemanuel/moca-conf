<?php

namespace App\Enums;

enum ConferenceStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';
    case Cancelled = 'cancelled';
}
