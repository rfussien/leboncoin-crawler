<?php

namespace Lbc\Crawler;

use Lbc\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class SearchResultCrawlerTest extends TestCase
{
    protected $searchContent;
    protected $searchContent2;

    public function setUp()
    {
        $this->searchContent = file_get_contents($this->searchData['file']);

        /**
         * https://www.leboncoin.fr/telephonie/offres/basse_normandie/?f=a&th=1&q=iphone
         */
        $this->searchContent2 = file_get_contents($this->searchData2['file']);
    }

    public function testIVeGotSomeOfflineContent()
    {
        $this->assertNotEmpty($this->searchContent);
    }

    public function testTheCrawler()
    {
        $search = new SearchResultCrawler(
            new Crawler($this->searchContent),
            $this->searchData['url']
        );

        $expected = (new Crawler(file_get_contents($this->searchData['file'])))->html();

        $this->assertEquals($expected, $search->getCrawler()->html());
    }

    public function testTheOfflineContentHasTheRightNumberOfAdsAndPages()
    {
        $search = new SearchResultCrawler(
            new Crawler($this->searchContent),
            $this->searchData['url']
        );

        $this->assertEquals(73, $search->getNbAds());
        $this->assertEquals(3, $search->getNbPages());

        $search = new SearchResultCrawler(
            new Crawler($this->searchContent2),
            $this->searchData['url']
        );

        $this->assertEquals(1712, $search->getNbAds());
        $this->assertEquals(49, $search->getNbPages());
    }

    public function testTheAdsId()
    {
        $search = new SearchResultCrawler(
            new Crawler($this->searchContent),
            $this->searchData['url']
        );

        $expected = [
            '1110535422',
            '1110412134',
            '1110129810',
            '1109401173',
            '1108148434',
            '1079297441',
            '1108149758',
            '1108148328',
            '1108145134',
            '1107918067',
            '1108145753',
            '1107532564',
            '1106196938',
            '1107107942',
            '1093162181',
            '1106589622',
            '1106369942',
            '1105886886',
            '943884695',
            '1104009980',
            '1103457899',
            '1101895470',
            '1090883246',
            '1101234805',
            '1100488380',
            '985290444',
            '1099996342',
            '1098781309',
            '1074953857',
            '1097431762',
            '1097058873',
            '1096818484',
            '1095941298',
            '1095611649',
            '1094424477',
        ];

        $this->assertEquals($expected, $search->getAdsId());
    }

    public function testCheckTheContentOfTheFirstAds()
    {
        $search = new SearchResultCrawler(
            new Crawler($this->searchContent),
            $this->searchData['url']
        );

        $this->assertCount(35, $search->getAds());
    }
}
