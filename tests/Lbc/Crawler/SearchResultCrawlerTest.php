<?php namespace Lbc\Crawler;

use Lbc\Parser\SearchResultDateTimeParser;
use Symfony\Component\DomCrawler\Crawler;

class SearchResultCrawlerTest extends \PHPUnit_Framework_TestCase
{
    protected $searchContent;
    protected $searchContent2;

    protected $adContent;

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

        /**
         * http://www.leboncoin.fr/voitures/753398357.htm?ca=4_s
         */
        $this->adContent = file_get_contents(dirname(dirname(__DIR__)) . '/content/search_result_ad.html');
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

        $this->assertEquals(650, $search->getNbAds());
        $this->assertEquals(19, $search->getNbPages());

        $search = new SearchResultCrawler($this->searchContent2);

        $this->assertEquals(1801, $search->getNbAds());
        $this->assertEquals(52, $search->getNbPages());
    }

    public function testTheInformationOfAnAdAreCorrectlyExtracted()
    {
        $search = new SearchResultCrawler($this->searchContent);

        $node = (new Crawler($this->adContent))->filter('.list-lbc > a')->first();

        $expected = (object)[
            'id'         => '753398357',
            'title'      => 'Volkswagen TOUAREG RLINE 245ch V6',
            'price'      => '49990',
            'url'        => 'http://www.leboncoin.fr/voitures/753398357.htm?ca=4_s',
            'created_at' => SearchResultDateTimeParser::toDt('5 jan', '19:58'),
            'thumb'      => 'http://193.164.197.60/thumbs/0a3/0a3d6148ed12dfa159bd124810f3bfd612d23e5f.jpg',
            'nb_image'   => '3',
            'placement'  => 'Colleville-Montgomery / Calvados',
            'pro'        => true,
        ];

        $this->assertEquals($expected, $search->getAd($node));
    }

    public function testCheckTheContentOfTheFirstAds()
    {
        $search = new SearchResultCrawler($this->searchContent);

        $this->assertEquals(35, count($search->getAds()));
    }
}
