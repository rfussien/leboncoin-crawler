<?php

namespace Lbc\Crawler;

use Symfony\Component\DomCrawler\Crawler;

class SearchResultCrawlerTest extends \PHPUnit_Framework_TestCase
{
    protected $searchContent;
    protected $searchContent2;

    public function setUp()
    {
        /*
         * http://www.leboncoin.fr/voitures/offres/basse_normandie/?f=a&th=1&ms=30000&me=100000&fu=2&gb=2
         */
        $this->searchContent = file_get_contents(dirname(dirname(__DIR__)) . '/content/search_result.html');

        /**
         * http://www.leboncoin.fr/telephonie/offres/basse_normandie/?f=a&th=1&q=iphone
         */
        $this->searchContent2 = file_get_contents(dirname(dirname(__DIR__)) . '/content/search_result2.html');
    }

    public function testIVeGotSomeOfflineContent()
    {
        $this->assertNotEmpty($this->searchContent);
    }

    public function testTheCrawler()
    {
        $search = new SearchResultCrawler($this->searchContent);

        $expected = (new Crawler($this->searchContent))->html();

        $this->assertEquals($expected, $search->getCrawler()->html());
    }

    public function testTheOfflineContentHasTheRightNumberOfAdsAndPages()
    {
        $search = new SearchResultCrawler($this->searchContent);

        $this->assertEquals(799, $search->getNbAds());
        $this->assertEquals(23, $search->getNbPages());

        $search = new SearchResultCrawler($this->searchContent2);

        $this->assertEquals(5047, $search->getNbAds());
        $this->assertEquals(145, $search->getNbPages());
    }

    public function testTheAdsId()
    {
        $search = new SearchResultCrawler($this->searchContent);

        $expected = [
            '1046613551', '1046609163', '1046583800', '1046572010', '1028952521',
            '1046447258', '1046411771', '1016363207', '1035200374', '1046352820',
            '1031298162', '1030691674', '1046321177', '1032248300', '1000713898',
            '1046288334', '1018108325', '1025795963', '1046233711', '1015545496',
            '1046218853', '1042802333', '1040954432', '1046387866', '996082887',
            '1035218075', '1033979644', '1046158278', '1046156273', '1046149609',
            '1046101160', '1046048811', '1046069122', '1046059631', '1046002930',
        ];

        $this->assertEquals($expected, $search->getAdsId());
    }

    public function testCheckTheContentOfTheFirstAds()
    {
        $search = new SearchResultCrawler($this->searchContent);

        $this->assertCount(35, $search->getAds());
    }
}
