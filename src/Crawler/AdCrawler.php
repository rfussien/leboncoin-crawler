<?php

namespace Lbc\Crawler;

use Lbc\Filter\CitySanitizer;
use Lbc\Filter\CpSanitizer;
use Lbc\Filter\DefaultSanitizer;
use Lbc\Filter\KeySanitizer;
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
                if (preg_match_all(
                    '#//img.+.leboncoin.fr/.*\.jpg#',
                    $crawler->html(),
                    $matches
                )) {
                    foreach ($matches[0] as $image) {
                        if (preg_match('/thumb/', $image)) {
                            array_push(
                                $images_thumbs,
                                (string)Http::createFromString($image)
                                    ->withScheme($this->sheme)
                            );

                            continue;
                        }

                        array_push(
                            $images,
                            (string)Http::createFromString($image)
                                ->withScheme($this->sheme)
                        );
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

        $properties = [
            'titre' => DefaultSanitizer::clean(
                $this->node->filter('h1')->text()
            )
        ];

        $node->filter('h2')
            ->each(function (Crawler $crawler) use (&$properties) {
                $properties = array_merge(
                    $properties,
                    $this->sanitize(
                        $crawler->filter('.property')->text(),
                        $crawler->filter('.value')->text()
                    )
                );
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
        return [
            'description' => $this->node
                ->filter("p[itemprop=description]")
                ->text()
        ];
    }

    /**
     * Transform the properties name into a snake_case string and sanitize
     * the value
     *
     * @param string $key
     * @param string $value
     * @return string
     */
    private function sanitize($key, $value)
    {
        $key = KeySanitizer::clean($key);

        if ($key == 'ville') {
            return [
                'ville' => CitySanitizer::clean($value),
                'cp'    => CpSanitizer::clean($value),
            ];
        }

        $filterName = 'Lbc\\Filter\\' . ucfirst($key) . 'Sanitizer';

        if (!class_exists($filterName)) {
            $filterName = 'Lbc\\Filter\\DefaultSanitizer';
        }

        return [$key => call_user_func("$filterName::clean", $value)];
    }
}
