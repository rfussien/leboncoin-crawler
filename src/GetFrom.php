<?php namespace Lbc;

use Lbc\Crawler\AdCrawler;
use Lbc\Crawler\SearchResultCrawler;
use Lbc\Parser\SearchResultUrlParser;

class GetFrom
{
    /**
     * retrieve the search result data from the given url
     *
     * @param $url
     * @return array
     */
    public static function search($url)
    {
        $searchData = new SearchResultCrawler(file_get_contents($url));
        $url = new SearchResultUrlParser($url, $searchData->getNbPages());

        $sumarize = [
            'total_ads'   => $searchData->getNbAds(),
            'total_page'  => $searchData->getNbPages(),
            'category'    => $url->getCategory(),
            'location'    => $url->getLocation(),
            'search_area' => $url->getSearchArea(),
            'sort_by'     => $url->getSortType(),
            'type'        => $url->getType(),
            'ads'         => $searchData->getAds(),
        ];

        return array_merge($url->getNav(), $sumarize);
    }

    /**
     * Retrieve the ad's data from an ad's ID and its category
     *
     * @param $url
     * @return array
     */
    public static function adById($id, $category)
    {
        return static::ad("http://www.leboncoin.fr/{$category}/{$id}.htm");
    }

    /**
     * Retrieve the ad's data from the given url
     *
     * @param $url
     * @return array
     */
    public static function ad($url)
    {
        $adData = new AdCrawler(file_get_contents($url));

        return $adData->getAll();
    }
}
