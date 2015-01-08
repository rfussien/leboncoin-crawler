<?php namespace Lbc\Parser;

use Carbon\Carbon;

class SearchResultDateTimeParserTest extends \PHPUnit_Framework_TestCase
{
    public function testAujoudHuiReturnTheDateOfToday()
    {
        $now = Carbon::now();

        $dt = SearchResultDateTimeParser::toDt("Aujourd'hui", "{$now->hour}:{$now->minute}");

        $expected = Carbon::today()->hour($now->hour)->minute($now->minute)->format('Y-m-d H:m');

        $this->assertEquals($expected, $dt);
    }

    public function testHierReturnTheDateOfYesterday()
    {
        $dt = SearchResultDateTimeParser::toDt("Hier", "7:22");
        $expected = Carbon::yesterday()->hour(7)->minute(22)->format('Y-m-d H:m');

        $this->assertEquals($expected, $dt);
    }

    public function testItReturnTheDateFromAString()
    {
        $dt = SearchResultDateTimeParser::toDt("29 nov", "7:22");

        $expected = new Carbon;
        $expected
            ->day(29)
            ->month(11)
            ->hour(7)
            ->minute(22);

        if ($expected > Carbon::now()) {
            $expected->year--;
        }

        $expected = $expected->format('Y-m-d H:m');

        $this->assertEquals($expected, $dt);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Unable to parse the month
     */
    public function testItThrowAnExceptionWhenTheMonthFormatIsWrong()
    {
        SearchResultDateTimeParser::toDt("29 whatever", "7:22");
    }
}
