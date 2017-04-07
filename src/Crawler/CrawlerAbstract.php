<?php

namespace Lbc\Crawler;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CrawlerAbstract
 * @package Lbc\Crawler
 */
abstract class CrawlerAbstract
{
    /**
     * @var string
     */
    protected $sheme = 'https';

    /**
     * @var Crawler
     */
    protected $node;

    /**
     * CrawlerAbstract constructor.
     * @param Crawler $node
     * @param $url
     */
    public function __construct(Crawler $node, $url)
    {
        $this->node = $node;

        $this->setUrlParser($url);
    }

    /**
     * Return the current node
     *
     * @return Crawler
     */
    public function getCrawler()
    {
        return $this->node;
    }

    /**
     * @return mixed
     */
    public function getUrlParser()
    {
        return $this->url;
    }

    /**
     * @param $url
     * @return mixed
     */
    abstract protected function setUrlParser($url);
}
