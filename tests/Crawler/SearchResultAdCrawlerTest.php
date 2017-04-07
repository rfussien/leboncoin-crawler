<?php

namespace Lbc\Crawler;

use Lbc\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class SearchResultAdCrawlerTest extends TestCase
{
    protected $adContent;

    public function setUp()
    {
        /**
         * https://www.leboncoin.fr/voitures/1110535422.htm?ca=4_s
         */
        $this->adContent = file_get_contents(dirname(__DIR__) . '/content/search_result.html');
    }

    public function testTheInformationOfAnAdAreCorrectlyExtracted()
    {
        $node = (new Crawler($this->adContent))->filter('[itemtype="http://schema.org/Offer"]')->first();

        $search = new SearchResultAdCrawler($node,
            $node->filter('a')->attr('href'));

        $expected = (object)[
            'id'            => '1110535422',
            'titre'         => 'BMW x6 3.0d 235cv Xdrive pack exclusive',
            'prix'          => 30999,
            'url'           => 'https://www.leboncoin.fr/voitures/1110535422.htm',
            'created_at'    => '2017-03-22 11:37',
            'images_thumbs' => 'https://img2.leboncoin.fr/ad-thumb/325cd6e285d766f98a0a4b17526d7d2685accbb0.jpg',
            'nb_image'      => 3,
            'placement'     => 'Caen / Calvados',
            'type'          => 'part',
        ];

        $this->assertEquals($expected, $search->getAll());
    }
}
