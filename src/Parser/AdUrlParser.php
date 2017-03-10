<?php

namespace Lbc\Parser;

use League\Uri\Components\Query;
use League\Uri\Modifiers\RemoveQueryKeys;
use League\Uri\Schemes\Http;

class AdUrlParser
{
    protected $url;

    protected $id;
    protected $category;

    protected $baseUrl = 'http://www.leboncoin.fr/';

    public function __construct($url)
    {
        $this->url = Http::createFromString($url);

        $this->url = (new RemoveQueryKeys($this->url->query->keys()))->__invoke($this->url);

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
