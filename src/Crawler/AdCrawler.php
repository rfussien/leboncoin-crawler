<?php

namespace Lbc\Crawler;

use Lbc\Helper\Encoding;
use League\Url\Url;
use Symfony\Component\DomCrawler\Crawler;

class AdCrawler extends CrawlerAbstract
{
    /**
     * Return a full ad information
     *
     * @return array
     */
    public function getAll()
    {
        return array_merge(
            ['thumbs' => $this->getThumbs()],
            ['pictures' => $this->getPictures()],
            $this->getCommonInfo(),
            ['criterias' => $this->getCriterias()],
            ['description' => $this->getDescription()]
        );
    }

    /**
     * Return an array with the Thumbs pictures url
     *
     * @param Crawler $node
     * @return array
     */
    public function getThumbs(Crawler $node = null)
    {
        if (!($node instanceof Crawler)) {
            $node = $this->crawler;
        }

        $pictures = [];

        $node
            ->filter('.lbcImages > meta[itemprop="image"]')
            ->each(function (Crawler $link, $i) use (&$pictures) {
                $pictures[$i] = Url::createFromUrl($link->attr('content'))
                    ->setScheme('http')
                    ->__toString();
            });

        return $pictures;
    }

    /**
     * Return an array with the pictures url
     *
     * @return array
     */
    public function getPictures()
    {
       return array_map(function ($picture) {
            return str_replace('thumbs', 'images', $picture);
        }, $this->getThumbs());
    }

    /**
     * Return the common information (price, cp, city)
     *
     * @param Crawler $node
     *
     * @return array
     */
    public function getCommonInfo(Crawler $node = null)
    {
        if (!($node instanceof Crawler)) {
            $node = $this->crawler;
        }

        $info = [];

        $info['title'] = $node->filter('#ad_subject')->text();

        list($info['price'], $info['city'], $info['cp']) = $node
            ->filter('.lbcParams')->first()->filter('td')
            ->each(function (Crawler $param) {
                return $param->text();
            });

        $info['price'] = (int)preg_replace('/\D/', '', $info['price']);

        return $info;
    }

    /**
     * Return the description
     *
     * @param Crawler $node
     * @return string
     */
    public function getDescription(Crawler $node = null)
    {
        if (!($node instanceof Crawler)) {
            $node = $this->crawler;
        }


        $description = $node->filter('.AdviewContent > .content')->html();
        $description = str_replace(["\n", '<br><br>', '<br>'], [' ', "\n", ' '], $description);
        $description = preg_replace('/ +/', ' ', $description);

        return trim($description);
    }

    /**
     * Return the criterias
     *
     * @param Crawler $node
     *
     * @return array
     */
    public function getCriterias(Crawler $node = null)
    {
        if (!($node instanceof Crawler)) {
            $node = $this->crawler;
        }

        $criterias = [];
        $node
            ->filter('div.criterias tr')
            ->each(function ($criteria) use (&$criterias) {
                $name = static::parseCriteriaName($criteria);
                $value = static::parseCriteriaValue($criteria);

                $criterias[$name] = $value;
            });

        return $criterias;
    }

    /**
     * Transform the criteria's name into a snake_case string
     *
     * @param Crawler $node
     * @return string
     */
    protected static function parseCriteriaName(Crawler $node)
    {
        return preg_replace(
            '/\s/',
            '_',
            trim(
                strtolower(
                    Encoding::toAscii($node->filter('th')->text())
                )
            )
        );
    }

    /**
     * Clean the data
     *
     * @param Crawler $node
     * @return string
     */
    protected static function parseCriteriaValue(Crawler $node)
    {
        return trim(
            preg_replace(
                '/document\.write\(.*\);/',
                '',
                $node->filter('td')->text()
            )
        );
    }
}
