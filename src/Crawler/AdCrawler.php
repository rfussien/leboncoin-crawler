<?php namespace Lbc\Crawler;

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
        isset($node) or $node = $this->crawler;

        $pictures = [];

        $node
            ->filter('#thumbs_carousel > a > span')
            ->each(function ($link, $i) use (&$pictures) {
                $pictures[$i] = preg_replace(
                    "/.*url\('(.*)'\);/",
                    '$1',
                    $link->attr('style')
                );
            });

        return $pictures;
    }

    /**
     * Return an array with the pictures url
     *
     * @param Crawler $node
     * @return array
     */
    public function getPictures(Crawler $node = null)
    {
        isset($node) or $node = $this->crawler;

        $pictures = [];

        foreach ($this->getThumbs() as $k => $v) {
            $k = preg_replace('/thumb/', 'picture', $k);
            $v = preg_replace('/thumbs/', 'images', $v);

            $pictures[$k] = $v;
        }

        return $pictures;
    }

    /**
     * Return the common informations
     *
     * @return array
     */
    public function getCommonInfo(Crawler $node = null)
    {
        isset($node) or $node = $this->crawler;

        $info = [];

        $info['title'] = $node->filter('#ad_subject')->text();

        list($info['price'], $info['city'], $info['cp']) = $node
            ->filter('.lbcParams')->first()->filter('td')
            ->each(function($param) {
                return $param->text();
            });

        $info['price'] = (int) preg_replace('/[^\d]/', '', $info['price']);

        return $info;
    }

    /**
     * Return the description
     *
     * @param Crawler $node
     * @return array
     */
    public function getDescription(Crawler $node = null)
    {
        isset($node) or $node = $this->crawler;

        return trim($node->filter('.AdviewContent > .content')->text());
    }

    /**
     * Return the criterias
     *
     * @return array
     */
    public function getCriterias(Crawler $node = null)
    {
        isset($node) or $node = $this->crawler;

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
     * @return mixed
     */
    protected static function parseCriteriaName(Crawler $node)
    {
        return preg_replace(
            '/\s/',
            '_',
            trim(
                strtolower(
                    toAscii($node->filter('th')->text())
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
