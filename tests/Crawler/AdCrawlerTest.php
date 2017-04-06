<?php

namespace Lbc\Crawler;

use Lbc\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class AdCrawlerTest extends TestCase
{
    /**
     * @var AdCrawler
     */
    protected $adCrawler;

    public function setUp()
    {
        $this->adCrawler = new AdCrawler(
            new Crawler(utf8_encode(file_get_contents($this->adData['file']))),
            $this->adData['url']
        );
    }

    public function testWeVeGotSomeOfflineContent()
    {
        $this->assertNotNull($this->adData['data']);
    }

    public function testWeRetrieveThePictures()
    {
        $this->assertEquals(
            $this->adData['data']['images'],
            $this->adCrawler->getPictures()['images']
        );

        $this->assertEquals(
            $this->adData['data']['images_thumbs'],
            $this->adCrawler->getPictures()['images_thumbs']
        );
    }

    public function testTheAdDescription()
    {
        $this->assertEquals(
            $this->adData['data']['description'],
            $this->adCrawler->getDescription()['description']
        );
    }

    public function testTheAdCriterias()
    {
        $this->assertEquals(
            $this->adData['data']['properties'],
            $this->adCrawler->getProperties()['properties']
        );
    }

    public function testTheFullAdInformation()
    {
        $this->assertEquals($this->adData['data'], $this->adCrawler->getAll());
    }
}
