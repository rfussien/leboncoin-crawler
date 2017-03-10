<?php

namespace Lbc\Crawler;

use Symfony\Component\DomCrawler\Crawler;

class SearchResultAdCrawlerTest extends \PHPUnit_Framework_TestCase
{
    protected $adContent;

    public function setUp()
    {
        /**
         * http://www.leboncoin.fr/voitures/917789397.htm?ca=4_s
         */
        $this->adContent = file_get_contents(dirname(dirname(__DIR__)) . '/content/search_result.html');
    }

    public function testTheInformationOfAnAdAreCorrectlyExtracted()
    {
        $node = (new Crawler($this->adContent))->filter('[itemtype="http://schema.org/Offer"] > a')->first();

        $search = new SearchResultAdCrawler($node);

        $expected = (object) [
            'id' => '1046613551',
            'title' => 'BMW 635d coupe',
            'price' => 33499,
            'url' => 'http://www.leboncoin.fr/voitures/1046613551.htm?ca=4_s',
            'created_at' => '2016-11-07 22:49',
            'thumb' => 'http://img2.leboncoin.fr/ad-thumb/cd38e9ebe6abc86e2568de2a4ab14e8fa9f5196f.jpg',
            'nb_image' => 3,
            'placement' => 'Manche',
            'type' => 'part',
        ];

        $this->assertEquals($expected, $search->getAll());
    }
}
