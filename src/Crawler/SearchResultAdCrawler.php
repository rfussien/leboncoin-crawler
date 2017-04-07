<?php

namespace Lbc\Crawler;

use Lbc\Filter\PrixSanitizer;
use Lbc\Parser\AdUrlParser;
use Lbc\Parser\SearchResultUrlParser;
use League\Uri\Schemes\Http;

/**
 * Class SearchResultAdCrawler
 * @package Lbc\Crawler
 */
class SearchResultAdCrawler extends CrawlerAbstract
{
    /**
     * @var AdUrlParser
     */
    protected $url;

    /**
     * @param $url
     * @return SearchResultUrlParser
     */
    protected function setUrlParser($url)
    {
        $this->url = new AdUrlParser($url);
    }

    /**
     * Return the Ad's ID
     *
     * @return string
     */
    public function getId()
    {
        return $this->url->getId();
    }


    /**
     * Return the title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getFieldValue($this->node->filter('h2'), '');
    }

    /**
     * Return the price
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->getFieldValue(
            $this->node->filter('*[itemprop=price]'),
            0,
            function ($value) {
                return PrixSanitizer::clean($value);
            }
        );
    }

    /**
     * Return the Ad's URL
     *
     * @return string
     */
    public function getUrl()
    {
        return (string)Http::createFromString($this->url)->withScheme('https');
    }

    /**
     * Return the data and time the ad was created
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->node
            ->filter('*[itemprop=availabilityStarts]')
            ->first()
            ->attr('content');
    }

    /**
     * Return the thumb picture url
     *
     * @return null|string
     */
    public function getThumb()
    {
        $image = $this->node
            ->filter('.item_imagePic .lazyload[data-imgsrc]')
            ->first();

        if (0 === $image->count()) {
            return null;
        }

        $src = $image
            ->attr('data-imgsrc');

        return (string)Http::createFromString($src)->withScheme('https');
    }

    /**
     * Return the number of picture of the ad
     *
     * @return int
     */
    public function getNbImage()
    {
        $node = $this->node->filter('.item_imageNumber');

        return $this->getFieldValue($node, 0);
    }

    /**
     * @return mixed
     */
    public function getPlacement()
    {
        $node = $this->node->filter('*[itemprop=availableAtOrFrom]');

        return $this->getFieldValue($node, '', function ($value) {
            return preg_replace('/\s+/', ' ', trim($value));
        });
    }

    /**
     * @return mixed
     */
    public function getIsPro()
    {
        return $this->getFieldValue(
            $this->node->filter('.ispro'),
            false,
            function ($value) {
                return true || $value;
            }
        );
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return [
            'id'            => $this->getId(),
            'titre'         => $this->getTitle(),
            'is_pro'        => $this->getIsPro(),
            'prix'          => $this->getPrice(),
            'url'           => $this->getUrl(),
            'created_at'    => $this->getCreatedAt(),
            'images_thumbs' => $this->getThumb(),
            'nb_image'      => $this->getNbImage(),
            'placement'     => $this->getPlacement(),
        ];
    }
}
