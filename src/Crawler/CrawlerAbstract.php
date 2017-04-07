<?php

namespace Lbc\Crawler;

use function foo\func;
use Lbc\Filter\DefaultSanitizer;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CrawlerAbstract
 * @package Lbc\Crawler
 */
abstract class CrawlerAbstract
{
    /**
     * @var
     */
    protected $url;

    /**
     * @var string
     */
    protected $sheme = 'https';

    /**
     * @var Crawler
     */
    protected $node;

    /**
     * CrawlerAbstract constructor.
     * @param Crawler $node
     * @param $url
     */
    public function __construct(Crawler $node, $url)
    {
        $this->node = $node;

        $this->setUrlParser($url);
    }

    /**
     * Return the current node
     *
     * @return Crawler
     */
    public function getCrawler()
    {
        return $this->node;
    }

    /**
     * @return mixed
     */
    public function getUrlParser()
    {
        return $this->url;
    }

    /**
     * Return the field's value
     *
     * @param Crawler $node
     * @param mixed $defaultValue
     * @param \Closure $callback
     * @param string $funcName
     * @param string $funcParam
     *
     * @return mixed
     */
    protected function getFieldValue(
        Crawler $node,
        $defaultValue,
        $callback = null,
        $funcName = 'text',
        $funcParam = ''
    ) {
        if ($callback == null) {
            $callback = function ($value) {
                return DefaultSanitizer::clean($value);
            };
        }

        if ($node->count()) {
            return $callback($node->$funcName($funcParam));
        }

        return $defaultValue;
    }

    /**
     * @param $url
     * @return mixed
     */
    abstract protected function setUrlParser($url);
}
