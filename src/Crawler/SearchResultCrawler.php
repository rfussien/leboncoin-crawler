<?php namespace Lbc\Crawler;

use Lbc\Parser\SearchResultDateTimeParser;
use Symfony\Component\DomCrawler\Crawler;

class SearchResultCrawler extends CrawlerAbstract
{
    /**
     * Return the total number of ads of the search
     *
     * @return int
     */
    public function getNbAds()
    {
        return (int)$this->crawler
            ->filter('nav > ul.navlist.type > li.selected > span.value > b')
            ->first()
            ->text();
    }

    /**
     * Return the number of page
     *
     * @return int
     */
    public function getNbPages()
    {
        return (int)ceil($this->getNbAds() / 35);
    }

    /**
     * Get an array containing the ads of the current result page
     *
     * @return Array
     */
    public function getAds()
    {
        $ads = array();

        $this->crawler->filter('div.list-lbc > a')
            ->each(function ($node) use (&$ads) {
                $ad = $this->getAd($node);
                $ads [$ad->id] = $ad;
            });

        return $ads;
    }

    /**
     * Return a parsed ads
     *
     * At the moment I'm writing this piece of code, an ads follow this
     * structure:
     *     <a href="http://www.leboncoin.fr/{{ $category }}/{{ $id }}.htm?ca=4_s" title="{{ $title }}">
     *         <div class="lbc">
     *             <div class="date">
     *                 <div>{{ $date }}</div>
     *                 <div>{{ $time }}</div>
     *             </div>
     *             <div class="image">
     *                 <div class="image-and-nb">
     *                     <img src="{{ $imageThumbUrl }}" alt="{{ $title }}">
     *                     <div class="nb">
     *                         <div class="top radius">&nbsp;</div>
     *                         <div class="value radius">{{ $nbImages}}</div>
     *                     </div>
     *                 </div>
     *             </div>
     *             <div class="detail">
     *                 <div class="title">{{ $title }}</div>
     *                 <div class="category">{{ $pro }}</div>
     *                 <div class="placement">{{ $placement }}</div>
     *                 <div class="price">{{ $price }}&nbsp;€</div>
     *             </div>
     *         </div>
     *     </a>
     *
     * @param $node
     * @return array
     */
    public function getAd(Crawler $node)
    {
        $url = $node->attr('href');

        $id = preg_replace('/\/\w+\/(\d+)\.htm/', '$1', parse_url($url)['path']);

        $title = $node->attr('title');

        $price = $node->filter('.price')->count() ? trim($node->filter('.price')->text()) : null;
        $price = preg_replace('/[^\d]/', '', $price);

        list($date, $time) = $node->filter('.date > div')
            ->each(function ($node) {
                return $node->text();
            });
        $created_at = SearchResultDateTimeParser::toDt($date, $time);

        $thumb = $node->filter('.image-and-nb > img')->attr('src');

        $nbImage = trim($node->filter('.image-and-nb > .nb > .value')->text());

        $placement = trim($node->filter('.placement')->text());
        $placement = preg_replace('/\s+/', ' ', $placement);

        $category = $node->filter('.detail > .category')->text();
        $category = preg_replace('/[\s()]+/', '', $category);

        $ads = (object) [
            'id'         => $id,
            'title'      => $title,
            'price'      => $price,
            'url'        => $url,
            'created_at' => $created_at,
            'thumb'      => $thumb,
            'nb_image'   => $nbImage,
            'placement'  => $placement,
            'pro'        => ($category == 'pro'),
        ];

        return $ads;
    }
}
