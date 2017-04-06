<?php

namespace Lbc\Crawler;

use Lbc\Filter\KeySanitizer;
use Lbc\Helper\Encoding;
use Lbc\Parser\AdUrlParser;
use League\Uri\Schemes\Http;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AdCrawler
 * @package Lbc\Crawler
 */
class AdCrawler extends CrawlerAbstract
{
    /**
     * @param $url
     * @return AdUrlParser
     */
    protected function setUrlParser($url)
    {
        $this->url = new AdUrlParser($url);
    }

    /**
     * Return a full ad information
     *
     * @return array
     */
    public function getAll()
    {
        return array_merge(
            [
                'id'       => $this->getUrlParser()->getId(),
                'category' => $this->getUrlParser()->getCategory(),
            ],
            $this->getPictures(),
            $this->getProperties(),
            $this->getDescription()
        );
    }

    /**
     * Return an array with the Thumbs pictures url
     *
     * @param Crawler $node
     * @return array
     */
    public function getPictures(Crawler $node = null)
    {
        $node = $node ?: $this->node;

        $images = [];
        $images_thumbs = [];

        $node
            ->filter('.adview_main script')
            ->each(function (Crawler $crawler) use (&$images, &$images_thumbs) {
                preg_match_all(
                    '#//img.+.leboncoin.fr/.*\.jpg#',
                    $crawler->html(),
                    $matches
                );

                if (count($matches[0]) > 0) {
                    foreach ($matches[0] as $image) {
                        if (preg_match('/thumb/', $image)) {
                            array_push(
                                $images_thumbs,
                                (string)Http::createFromString($image)
                                    ->withScheme($this->sheme)
                            );
                        } else {
                            array_push(
                                $images,
                                (string)Http::createFromString($image)
                                    ->withScheme($this->sheme)
                            );
                        }
                    }
                }
            });

        return [
            'images'        => $images,
            'images_thumbs' => $images_thumbs,
        ];
    }

    /**
     * Return the common information (price, cp, city)
     *
     * @param Crawler $node
     *
     * @return array
     */
    public function getProperties(Crawler $node = null)
    {
        $node = $node ?: $this->node;

        $properties = [];

        $properties['title'] = static::parseValue($this->node->filter('h1')->text());

        $node->filter('h2')
            ->each(function (Crawler $crawler) use (&$properties) {
                $property = KeySanitizer::clean(
                    $crawler->filter('.property')->text()
                );

                $value = static::parseValue(
                    $crawler->filter('.value')->text()
                );

                $properties[$property] = $value;
            });

        return ['properties' => $properties];
    }

    /**
     * Return the description
     *
     * @param Crawler $node
     * @return string
     */
    public function getDescription(Crawler $node = null)
    {
        return ['description' => $this->node->filter("p#description")->text()];
    }

    /**
     * Transform the properties name into a snake_case string
     *
     * @param string $value
     * @return string
     */
    protected static function parseProperty($value)
    {
        return preg_replace(
            '/\s/',
            '_',
            trim(strtolower(Encoding::toAscii($value)))
        );
    }

    /**
     * Clean the data
     *
     * @param string $value
     * @return string
     */
    protected static function parseValue($value)
    {
        return trim(preg_replace("/[\n]+/", ' ', $value));
    }
}
