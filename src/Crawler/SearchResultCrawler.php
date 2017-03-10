<?php

namespace Lbc\Crawler;

class SearchResultCrawler extends CrawlerAbstract
{
    /**
     * Return the total number of ads of the search
     *
     * @return int
     */
    public function getNbAds()
    {
        $nbAds = $this->crawler
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

        $this->crawler->filter('[itemtype="http://schema.org/Offer"] > a')
            ->each(function ($node) use (&$ads) {
                $ad = (new SearchResultAdCrawler($node))->getAll();
                $ads [$ad->id] = $ad;
            });

        return $ads;
    }

    /**
     * Return the Ad's ID
     *
     * @return array
     */
    public function getAdsId()
    {
        $adsID = array();

        $this->crawler->filter('[itemtype="http://schema.org/Offer"] > a')
            ->each(function ($node) use (&$adsID) {
                $adsID [] = (new SearchResultAdCrawler($node))->getId();
            });

        return $adsID;
    }
}
