<?php namespace Lbc\Parser;

use League\Url\UrlImmutable as Url;

class SearchResultUrlParser
{
    protected $url;

    protected $baseUrl = 'http://www.leboncoin.fr/';

    protected $nbPages;

    /**
     * @param $url
     * @param int $nbPages
     */
    function __construct($url, $nbPages = 1)
    {
        if (!preg_match('/^.*leboncoin.fr/', $url)) {
            $url = preg_replace('/^[\/]?/', $this->baseUrl, $url);
        }

        $this->url = Url::createFromUrl($url);
        $this->nbPages = $nbPages;
    }

    public function current()
    {
        // set the default page to 1 unless it is set
        $query = $this->url->getQuery();
        isset($query['o']) or $query['o'] = 1;

        // remove th (thumb image)
        unset($query['th']);

        return $this->url->setQuery($query);
    }

    /**
     * Return the next page URL or null if none
     *
     * @return Url
     */
    public function next()
    {
        if ($this->current()->getQuery()['o'] >= $this->nbPages) {
            return null;
        }

        return $this->getIndexUrl(+1);
    }

    /**
     * Return the previous page URL or null if none
     *
     * @return Url
     */
    public function previous()
    {
        if ($this->current()->getQuery()['o'] == 1) {
            return null;
        }

        return $this->getIndexUrl(-1);
    }

    public function getIndexUrl($index)
    {
        $query = $this->current()->getQuery();
        $query['o'] += $index;

        return $this->url->setQuery($query);
    }

    /**
     * Return a meta array containing the nav links and the page
     *
     * @return array
     */
    public function getNav()
    {
        return [
            'page' => (int) $this->current()->getQuery()['o'],
            'links' => [
                'current'  => (string) $this->current(),
                'previous' => (string) $this->previous(),
                'next'     => (string) $this->next(),
            ]
        ];
    }

    /**
     * Return the category
     *
     * @return string
     */
    public function getCategory()
    {
        $category = $this->current()->getPath()[0];

        if ($category === 'annonces') {
            return null;
        }

        return $category;
    }

    /**
     * Return the search area
     *
     * @return string
     */
    public function getSearchArea()
    {
        if ($this->current()->getPath()[3] === 'occasions') {
            return 'toute la france';
        }

        if ($this->current()->getPath()[3] === 'bonnes_affaires') {
            return 'regions voisines';
        }

        return $this->current()->getPath()[2];
    }

    /**
     * Return one or more location (separated by a comma) or null if none
     *
     * @return null
     */
    public function getLocation()
    {
        return $this->current()->getQuery()['location'] ?: null;
    }

    /**
     * Return the type of the ads
     *
     * @return string all|part|pro
     */
    public function getType()
    {
        switch ($this->current()->getQuery()['f']) {
            case 'p':
                return 'part';
            case 'c':
                return 'pro';
        }

        return 'all';
    }

    /**
     * Return the sorting type
     *
     * @return string price|date
     */
    public function getSortType()
    {
        if ($this->current()->getQuery()['sp'] == 1) {
            return 'price';
        }

        return 'date';
    }
}
