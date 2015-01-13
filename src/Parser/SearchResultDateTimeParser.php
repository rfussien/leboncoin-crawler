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

    /**
     * Return a DateTime from a date string and a time string
     *
     * @param $date
     * @param $time
     * @return Carbon
     */
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
                $dt->month(self::getMonthNumber($month));
        }

        // Set up the time
        list ($hour, $minute) = preg_split('/:/', $time);
        $dt->hour = $hour;
        $dt->minute = $minute;

        if ($dt->gt(Carbon::tomorrow()->subSecond())) {
            $dt->year--;
        }

        return $dt->format('Y-m-d H:m');
    }

    /**
     * @param $month
     * @return int
     */
    private static function getMonthNumber($month)
    {
        foreach (self::$month as $monthName => $monthNumber) {
            if (preg_match("/$month/", $monthName)) {
                return $monthNumber;
            }
        }

        throw new \InvalidArgumentException('Unable to parse the month');
    }
}
