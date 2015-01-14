<?php namespace Lbc\Crawler;

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
            ->filter('nav > ul.navlist.type > li.selected > span.value > b')
            ->first()
            ->text();

        $nbAds = preg_replace('/\s+/', '', $nbAds);

        return (int) $nbAds;
    }

    /**
     * Return the number of page
     *
     * @return int
     */
    public function getNbPages()
    {
        return (int) ceil($this->getNbAds() / 35);
    }

    /**
     * Get an array containing the ads of the current result page
     *
     * @return Array
     */
    public function getAds()
    {
        $ads = array();

        $this->crawler->filter('div.list-lbc > a')
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

        $this->crawler->filter('div.list-lbc > a')
            ->each(function ($node) use (&$adsID) {
                $adsID [] = (new SearchResultAdCrawler($node))->getId();
            });

        return $adsID;
    }
}
