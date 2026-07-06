<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class RaspiStatus
{
    public static function isOnline(): bool
    {
        $raw = Cache::get('raspi_last_seen');
        if (!$raw) {
            return false;
        }

        return Carbon::parse($raw)->diffInSeconds(now()) < 30;
    }
}