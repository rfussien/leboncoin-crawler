<?php namespace Lbc\Parser;

use League\Url\Url;

class AdUrlParser
{
    protected $url;

    protected $id;
    protected $category;

    protected $baseUrl = 'http://www.leboncoin.fr/';

    public function __construct($url)
    {
        $this->url = Url::createFromUrl($url);

        /**
         * Clean the URL by removing every params (no need)
         */
        $query = $this->url->getQuery();
        foreach ($query->keys() as $paramName) {
            unset($query[$paramName]);
        }

        preg_match(
            '/\/(.*)\/(.*)\.htm/',
            parse_url($this->url, PHP_URL_PATH),
            $matches
        );

        $this->category = $matches[1];
        $this->id = $matches[2];
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUrl()
    {
        return (string) $this->url;
    }
}
