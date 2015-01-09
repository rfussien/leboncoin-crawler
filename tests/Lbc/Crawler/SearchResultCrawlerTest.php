<?php namespace Lbc\Crawler;

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

    public function testTheAdsId()
    {
        $search = new SearchResultCrawler($this->searchContent);

        $expected = [
            '753862363', '753850295', '753843597', '753832063', '753825535',
            '753824979', '729205464', '742650820', '750621938', '690318904',
            '753766957', '753764441', '742651186', '753759783', '748237410',
            '729132192', '753726642', '753723041', '702115601', '733979787',
            '728015610', '746360177', '744534260', '691276807', '753354997',
            '691269084', '712484305', '662053785', '710735683', '750941004',
            '746609897', '746990846', '753621234', '753606675', '746080950'
        ];

        $this->assertEquals($expected, $search->getAdsId());
    }

    public function testCheckTheContentOfTheFirstAds()
    {
        $search = new SearchResultCrawler($this->searchContent);

        $this->assertEquals(35, count($search->getAds()));
    }
}
