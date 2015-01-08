<?php namespace Lbc\Parser;

use Carbon\Carbon;

class SearchResultDateTimeParserTest extends \PHPUnit_Framework_TestCase
{
    public function testAujoudHuiReturnTheDateOfToday()
    {
        $dt = SearchResultDateTimeParser::toDt("Aujourd'hui", "7:22");
        $expected = Carbon::today()->hour(7)->minute(22);

        $this->assertEquals($expected, $dt);
    }

    public function testHierReturnTheDateOfYesterday()
    {
        $dt = SearchResultDateTimeParser::toDt("Hier", "7:22");
        $expected = Carbon::yesterday()->hour(7)->minute(22);

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

        $this->assertEquals($expected, $dt);
    }
}
