<?php

namespace Lbc\Crawler;

use Lbc\Parser\SearchResultUrlParser;

/**
 * Class SearchResultCrawler
 * @package Lbc\node
 */
class SearchResultCrawler extends CrawlerAbstract
{
    /**
     * @var SearchResultUrlParser
     */
    protected $url;

    /**
     * @param $url
     * @return SearchResultUrlParser
     */
    protected function setUrlParser($url)
    {
        $this->url = new SearchResultUrlParser($url);
    }

    /**
     * Return the total number of ads of the search
     *
     * @return int
     */
    public function getNbAds()
    {
        $nbAds = $this->node
            ->filter('a.tabsSwitch span.tabsSwitchNumbers')
            ->first();

        if ($nbAds->count()) {
            $nbAds = preg_replace('/\s+/', '', $nbAds->text());
            return (int) $nbAds;
        }

        return 0;
    }

    /**
     * Return the number of ads per page.
     *
     * Could be dynamically guessed in future, if Leboncoin change it frequently
     * Or if they add the ability for user to change it on result pages.
     *
     *
     * @return int
     */
    public function getNbAdsPerPage()
    {
        return 35;
    }

    /**
     * Return the number of page
     *
     * @return int
     */
    public function getNbPages()
    {
        return (int) ceil($this->getNbAds() / $this->getNbAdsPerPage());
    }

    /**
     * Get an array containing the ads of the current result page
     *
     * @return array
     */
    public function getAds()
    {
        $ads = array();

        $this->node->filter('[itemtype="http://schema.org/Offer"]')
            ->each(function ($node) use (&$ads) {
                $ad = (new SearchResultAdCrawler(
                    $node,
                    $node->filter('a')->attr('href')
                ))->getAll();

                $ads [$ad['id']] = $ad;
            });

        return $ads;
    }

    /**
     * Return the Ads's ID only
     *
     * @return array
     */
    public function getAdsId()
    {
        return array_keys($this->getAds());
    }
}
