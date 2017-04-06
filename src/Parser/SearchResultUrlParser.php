<?php

namespace Lbc\Parser;

use League\Uri\Modifiers\MergeQuery;
use League\Uri\Modifiers\RemoveQueryKeys;
use League\Uri\Schemes\Http;
use Psr\Http\Message\UriInterface;

/**
 * Class SearchResultUrlParser
 * @package Lbc\Parser
 */
class SearchResultUrlParser
{
    /**
     * @var Http
     */
    protected $url;

    /**
     * @var int
     */
    protected $nbPages;

    /**
     * @param string $url
     * @param int $nbPages
     */
    public function __construct($url, $nbPages = 1)
    {
        $this->url = Http::createFromString($url);

        $this->nbPages = $nbPages;
    }

    /**
     * @return Http
     */
    public function current()
    {
        // set the default page to 1 unless it is set
        if (!$this->url->query->hasKey('o')) {
            $newUrl = new MergeQuery('o=1');
            $this->url = $newUrl($this->url);
        }

        // remove th (thumb image)
        $newUrl = new RemoveQueryKeys(['th']);
        $this->url = $newUrl($this->url);

        return $this->url;
    }

    /**
     * Return the next page URL or null if non.
     *
     * @return UriInterface
     */
    public function next()
    {
        if ((int) $this->current()->query->getValue('o') >= $this->nbPages) {
            return null;
        }

        return $this->getIndexUrl(+1);
    }

    /**
     * Return the previous page URL or null if none
     *
     * @return UriInterface
     */
    public function previous()
    {
        if ((int) $this->current()->query->getValue('o') === 1) {
            return null;
        }

        return $this->getIndexUrl(-1);
    }

    /**
     * @param int $index
     *
     * @return UriInterface
     */
    public function getIndexUrl($index)
    {
        $oParam = (int) $this->current()->query->getValue('o') + $index;
        $newQuery = new MergeQuery('o='.$oParam);

        return $newQuery($this->url);
    }

    /**
     * Return a meta array containing the nav links and the page
     *
     * @return array
     */
    public function getNav()
    {
        return [
            'page' => (int) $this->current()->query->getValue('o'),
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
        $category = $this->current()->path->getSegment(0);

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
        if ($this->current()->path->getSegment(3) === 'occasions') {
            return 'toute la france';
        }

        if ($this->current()->path->getSegment(3) === 'bonnes_affaires') {
            return 'regions voisines';
        }

        return $this->current()->path->getSegment(2);
    }

    /**
     * Return one or more location (separated by a comma) or null if none
     *
     * @return null
     */
    public function getLocation()
    {
        return $this->current()->query->getValue('location', null);
    }

    /**
     * Return the type of the ads
     *
     * @return string all|part|pro
     */
    public function getType()
    {
        switch ($this->current()->query->getValue('f')) {
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
        if ((int) $this->current()->query->getValue('sp') === 1) {
            return 'price';
        }

        return 'date';
    }
}
