<?php namespace Lbc\Parser;

use Carbon\Carbon;

class SearchResultDateTimeParser
{
    private static $month = [
        'janvier'   => 1,
        'février'   => 2,
        'mars'      => 3,
        'avril'     => 4,
        'mai'       => 5,
        'juin'      => 6,
        'juillet'   => 7,
        'août'      => 8,
        'septembre' => 9,
        'octobre'   => 10,
        'novembre'  => 11,
        'décembre'  => 12
    ];

    public static function toDt($date, $time)
    {
        switch ($date) {
            case "Aujourd'hui":
                $dt = Carbon::today();
                break;
            case "Hier":
                $dt = Carbon::yesterday();
                break;
            default:
                list ($day, $month) = preg_split('/\s/', $date);

                $dt = new Carbon();
                $dt->day($day);
                $dt->month(static::getMonthNumber($month));
        }

        // Set up the time
        list ($hour, $minute) = preg_split('/:/', $time);
        $dt->hour = $hour;
        $dt->minute = $minute;

        if ($dt > Carbon::now()) {
            $dt->year--;
        }

        return $dt;
    }

    private static function getMonthNumber($month)
    {
        return array_values(
            array_filter(
                static::$month,
                function ($monthName) use ($month) {
                    return preg_match("/$month/", $monthName);
                },
                ARRAY_FILTER_USE_KEY
            )
        )[0];
    }
}
