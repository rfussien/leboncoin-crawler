<?php namespace Lbc\Crawler;

use Symfony\Component\DomCrawler\Crawler;

abstract class CrawlerAbstract
{
    protected $crawler;

    /**
     * @param $payload
     */
    public function __construct($payload = null)
    {
        $this->crawler = new Crawler($payload);
    }

    /**
     * Return the crawler
     *
     * @return Crawler
     */
    public function getCrawler()
    {
        return $this->crawler;
    }
}
