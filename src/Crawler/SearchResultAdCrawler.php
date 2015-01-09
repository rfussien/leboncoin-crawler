<?php namespace Lbc\Crawler;

use Lbc\Parser\SearchResultDateTimeParser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * At the moment I'm writing this piece of code, an ads follow this
 * structure:
 *
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
 */
class SearchResultAdCrawler
{
    protected $node;


    public function __construct(Crawler $node)
    {
        $this->node = $node;
        $this->url = $node->attr('href');

        return $this;
    }

    /**
     * Return the Ad's ID
     *
     * @return string
     */
    public function getId()
    {
        $path = parse_url($this->url)['path'];

        return preg_replace('/\/\w+\/(\d+)\.htm/', '$1', $path);
    }

    public function getTitle()
    {
        return trim($this->node->attr('title'));
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        $node = $this->node->filter('.price');

        $price = 0;
        if ($node->count()) {
            $price = (int) preg_replace('/[^\d]/', '', trim($node->text()));
        }

        return $price;
    }

    /**
     * Return the Ad's URL
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Return the data and time the ad was created
     *
     * @return string
     */
    public function getCreatedAt()
    {
        list($date, $time) = $this->node
            ->filter('.date > div')
            ->each(function ($node) {
                return $node->text();
            });

        return SearchResultDateTimeParser::toDt($date, $time);
    }

    /**
     * Return the thumb picture url
     *
     * @return null|string
     */
    public function getThumb()
    {
        $node = $this->node->filter('.image-and-nb > img');

        $thumb = null;
        if ($node->count()) {
            $thumb = $node->attr('src');
        }

        return $thumb;
    }

    /**
     * Return the number of picture of the ad
     *
     * @return int
     */
    public function getNbImage()
    {
        $node = $this->node->filter('.image-and-nb > .nb > .value');

        $nbImage = 0;
        if ($node->count()) {
            $nbImage = (int)trim($node->text());
        }

        return $nbImage;
    }

    /**
     * @return mixed
     */
    public function getPlacement()
    {
        $node = $this->node->filter('.placement');

        $placement = '';
        if ($node->count()) {
            $placement = preg_replace('/\s+/', ' ', trim($node->text()));
        }

        return $placement;
    }

    public function getIsPro()
    {
        $node = $this->node->filter('.detail > .category');
        $isPro = false;

        if ($node->count()) {
            $isPro = preg_replace('/[\s()]+/', '', $node->text());
        }

        return ($isPro == 'pro');
    }

    public function getAll()
    {
        return (object)[
            'id'         => $this->getId(),
            'title'      => $this->getTitle(),
            'price'      => $this->getPrice(),
            'url'        => $this->getUrl(),
            'created_at' => $this->getCreatedAt(),
            'thumb'      => $this->getThumb(),
            'nb_image'   => $this->getNbImage(),
            'placement'  => $this->getPlacement(),
            'is_pro'     => $this->getIsPro(),
        ];
    }
}
