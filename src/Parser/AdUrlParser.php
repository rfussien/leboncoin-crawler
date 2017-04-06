<?php

namespace Lbc\Parser;

use League\Uri\Modifiers\RemoveQueryKeys;
use League\Uri\Schemes\Http;

/**
 * Class AdUrlParser
 * @package Lbc\Parser
 */
class AdUrlParser
{
    /**
     * @var \League\Uri\Interfaces\Uri|\Psr\Http\Message\UriInterface
     */
    protected $url;

    /**
     * @var
     */
    protected $id;
    /**
     * @var
     */
    protected $category;

    /**
     * AdUrlParser constructor.
     * @param $url
     */
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

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->url;
    }
}
