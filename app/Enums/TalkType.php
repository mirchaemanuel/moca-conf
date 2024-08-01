<?php

namespace App\Enums;

enum TalkType: string
{
    case Keynote = 'keynote';
    case Workshop = 'workshop';
    case LightningTalk = 'lightning-talk';
}
