<?php namespace Lbc\Crawler;

use Lbc\Parser\SearchResultDateTimeParser;
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
        $node = (new Crawler($this->adContent))->filter('.list-lbc > a')->first();

        $search = new SearchResultAdCrawler($node);

        $expected = (object)[
            'id'         => '896305873',
            'title'      => 'Mercedes Classe B II 180 Design Automatique Diesel',
            'price'      => 20500,
            'url'        => 'http://www.leboncoin.fr/voitures/896305873.htm?ca=4_s',
            'created_at' => SearchResultDateTimeParser::toDt("Aujourd'hui", '20:01'),
            'thumb'      => 'http://img3.leboncoin.fr/thumbs/67d/67db00ee1186b81bd3177f0d9b92fe8d012f1778.jpg',
            'nb_image'   => 3,
            'placement'  => 'Pont-l\'Evêque / Calvados',
            'type'       => 'part',
        ];

        $this->assertEquals($expected, $search->getAll());
    }
}
