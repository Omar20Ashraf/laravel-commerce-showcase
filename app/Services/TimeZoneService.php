<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonImmutable;

class TimeZoneService extends Carbon
{
    public static function createFromClient($date, ?string $clientTimezone = null)
    {
        $timezone = $clientTimezone ?? request()->header(config('app.client_timezone_request_header_name'));

        if (in_array($timezone, timezone_identifiers_list())) {

            return Carbon::parse($date, $timezone)->setTimezone(config('app.timezone'));
        }

        return CarbonImmutable::parse($date);
    }

    public static function createFromServer(string $date, ?string $clientTimezone = null)
    {
        $timezone = $clientTimezone;

        if ($clientTimezone) {
            $timezone = request()->header(config('app.client_timezone_request_header_name'));

            config(['app.timezone' => $timezone]);

            return CarbonImmutable::parse($date)->setTimezone($timezone);
        }

        return CarbonImmutable::parse($date);
    }
}
