<?php namespace Lbc\Crawler;

use Lbc\Parser\SearchResultDateTimeParser;
use Symfony\Component\DomCrawler\Crawler;

class SearchResultAdCrawlerTest extends \PHPUnit_Framework_TestCase
{
    protected $adContent;

    public function setUp()
    {
        /**
         * http://www.leboncoin.fr/voitures/753398357.htm?ca=4_s
         */
        $this->adContent = file_get_contents(dirname(dirname(__DIR__)) . '/content/search_result_ad.html');
    }

    public function testTheInformationOfAnAdAreCorrectlyExtracted()
    {
        $node = (new Crawler($this->adContent))->filter('.list-lbc > a')->first();

        $search = new SearchResultAdCrawler($node);

        $expected = (object)[
            'id'         => '753398357',
            'title'      => 'Volkswagen TOUAREG RLINE 245ch V6',
            'price'      => 49990,
            'url'        => 'http://www.leboncoin.fr/voitures/753398357.htm?ca=4_s',
            'created_at' => SearchResultDateTimeParser::toDt('5 jan', '19:58'),
            'thumb'      => 'http://193.164.197.60/thumbs/0a3/0a3d6148ed12dfa159bd124810f3bfd612d23e5f.jpg',
            'nb_image'   => 3,
            'placement'  => 'Colleville-Montgomery / Calvados',
            'type'       => 'pro',
        ];

        $this->assertEquals($expected, $search->getAll());
    }
}
