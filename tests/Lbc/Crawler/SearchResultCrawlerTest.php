<?php namespace Lbc\Crawler;

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

        $this->assertEquals(690, $search->getNbAds());
        $this->assertEquals(20, $search->getNbPages());

        $search = new SearchResultCrawler($this->searchContent2);

        $this->assertEquals(1965, $search->getNbAds());
        $this->assertEquals(57, $search->getNbPages());
    }

    public function testTheAdsId()
    {
        $search = new SearchResultCrawler($this->searchContent);

        $expected = [
            '896305873', '918388326', '918353717', '891325771', '918340050',
            '918339265', '918335090', '914545627', '918236863', '899214543',
            '918145675', '917182471', '918130073', '902341065', '911107968',
            '918099489', '918099192', '918057958', '918055703', '918041045',
            '918035420', '918115788', '918015740', '869266253', '917977535',
            '917967176', '879087195', '917921773', '917920350', '898591934',
            '917934779', '917937286', '917869520', '917934759', '917789397',
        ];

        $this->assertEquals($expected, $search->getAdsId());
    }

    public function testCheckTheContentOfTheFirstAds()
    {
        $search = new SearchResultCrawler($this->searchContent);

        $this->assertEquals(35, count($search->getAds()));
    }
}
