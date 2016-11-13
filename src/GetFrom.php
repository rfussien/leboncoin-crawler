<?php namespace Lbc;

use GuzzleHttp\Client;
use Lbc\Crawler\AdCrawler;
use Lbc\Crawler\SearchResultCrawler;
use Lbc\Parser\AdUrlParser;
use Lbc\Parser\SearchResultUrlParser;

class GetFrom
{
    protected $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client();
    }

    /**
     * Return the http client
     * (usefull to mock the response for unit testing)
     *
     * @return Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * retrieve the search result data from the given url
     *
     * @param $url
     * @param bool $detailedAd
     *
     * @return array
     */
    public function search($url, $detailedAd = false)
    {
        $searchData = new SearchResultCrawler(
            (string) $this->httpClient->get($url)->getBody()
        );

        $url = new SearchResultUrlParser($url, $searchData->getNbPages());

        $ads = ($detailedAd) ? $searchData->getAds() : $searchData->getAdsId();

        $sumarize = [
            'total_ads'   => $searchData->getNbAds(),
            'total_page'  => $searchData->getNbPages(),
            'ads_per_page'  => $searchData->getNbAdsPerPage(),
            'category'    => $url->getCategory(),
            'location'    => $url->getLocation(),
            'search_area' => $url->getSearchArea(),
            'sort_by'     => $url->getSortType(),
            'type'        => $url->getType(),
            'ads'         => $ads,
        ];

        return array_merge($url->getNav(), $sumarize);
    }

    /**
     * Retrieve the ad's data from an ad's ID and its category
     *
     * @param $url
     *
     * @return array
     */
    private function adById($id, $category)
    {
        return $this->ad("http://www.leboncoin.fr/{$category}/{$id}.htm");
    }

    /**
     * Retrieve the ad's data from the given url
     *
     * @param $url
     * @return array
     */
    private function adByUrl($url)
    {
        $urlParser = new AdUrlParser($url);

        $adData = new AdCrawler(
            (string) $this->httpClient->get($url)->getBody()
        );

        return array_merge(
            [
                'id' => $urlParser->getId(),
                'category'=>$urlParser->getCategory()
            ],
            $adData->getAll()
        );
    }

    /**
     * Dynamique method to retrive the data by url OR id and category
     *
     * @return bool|mixed
     */
    public function ad()
    {
        if (func_num_args() == 1) {
            return call_user_func_array([$this, 'adByUrl'], func_get_args());
        }

        if (func_num_args() == 2) {
            return call_user_func_array([$this, 'adById'], func_get_args());
        }

        throw new \InvalidArgumentException('Bad number of argument');
    }

    public function __call($method, $arguments)
    {
        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $arguments);
        }

        throw new \BadMethodCallException();
    }

    /**
     * Add a little bit of sugar
     *
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        $instance = new self;

        if (method_exists($instance, $method)) {
            return call_user_func_array([$instance, $method], $arguments);
        }

        throw new \BadMethodCallException();
    }
}
