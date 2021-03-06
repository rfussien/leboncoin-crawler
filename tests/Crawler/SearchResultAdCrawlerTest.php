<?php

namespace Lbc\Crawler;

use Lbc\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class SearchResultAdCrawlerTest extends TestCase
{
    protected $adContent;

    public function setUp()
    {
        $this->adContent = file_get_contents(dirname(__DIR__) . '/content/search_result.html');
    }

    public function testTheInformationOfAnAdAreCorrectlyExtracted()
    {
        $node = (new Crawler($this->adContent))->filter('[itemtype="http://schema.org/Offer"]')->first();

        $search = new SearchResultAdCrawler(
            $node,
            $node->filter('a')->attr('href')
        );

        $expected = [
            'id'            => '1110535422',
            'titre'         => 'BMW x6 3.0d 235cv Xdrive pack exclusive',
            'is_pro'        => false,
            'prix'          => 30999,
            'url'           => 'https://www.leboncoin.fr/voitures/1110535422.htm',
            'created_at'    => '2017-03-22',
            'images_thumbs' => 'https://img2.leboncoin.fr/ad-thumb/325cd6e285d766f98a0a4b17526d7d2685accbb0.jpg',
            'nb_image'      => 3,
            'placement'     => 'Caen / Calvados',
        ];

        $this->assertEquals($expected, $search->getAll());
    }

    public function testNoThumbReturnNull()
    {
        $search = new SearchResultAdCrawler(
            new Crawler,
            'https://www.leboncoin.fr/voitures/1110535422.htm'
        );

        $this->assertEquals(null, $search->getThumb());
    }

    public function testTheDefaultValueIsReturned()
    {
        $search = new SearchResultAdCrawler(
            new Crawler,
            'https://www.leboncoin.fr/voitures/1110535422.htm'
        );

        $this->assertEquals(0, $search->getNbImage());
    }
}
