<?php namespace Lbc\Crawler;

use League\Url\Url;
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
    protected $url;

    public function __construct(Crawler $node)
    {
        $this->node = $node;
        $this->url = $node->attr('href');
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

    /**
     * Return the title
     *
     * @return mixed
     */
    public function getTitle()
    {
        return $this->getFieldValue($this->node, 0, function ($value) {
            return trim($value);
        }, 'attr', 'title');
    }

    /**
     * Return the price
     *
     * @return int
     */
    public function getPrice()
    {
        $node = $this->node->filter('*[itemprop=price]');

        return $this->getFieldValue($node, 0, function ($value) {
            return (int) preg_replace('/[^\d]/', '', trim($value));
        });
    }

    /**
     * Return the Ad's URL
     *
     * @return string
     */
    public function getUrl()
    {
        return Url::createFromUrl($this->url)
            ->setScheme('http')
            ->__toString();
    }

    /**
     * Return the data and time the ad was created
     *
     * @return string
     */
    public function getCreatedAt()
    {
        $node = $this->node
            ->filter('*[itemprop=availabilityStarts]')
            ->first()
        ;

        $date = $node->attr('content');

        $time = $this->getFieldValue($node, 0, function ($value) {
            $value = trim($value);

            return substr($value, strpos($value, ',') + 2);
        });

        return $date.' '.$time;
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
            return;
        }

        $src = $image
            ->attr('data-imgsrc')
        ;

        return Url::createFromUrl($src)
                ->setScheme('http')
                ->__toString()
        ;
    }

    /**
     * Return the number of picture of the ad
     *
     * @return int
     */
    public function getNbImage()
    {
        $node = $this->node->filter('.item_imageNumber');

        return $this->getFieldValue($node, 0, function ($value) {
            return (int)trim($value);
        });
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
    public function getType()
    {
        $node = $this->node->filter('*[itemprop=category]');

        return $this->getFieldValue($node, false, function ($value) {
            if ('pro' == preg_replace('/[\s()]+/', '', $value)) {
                return 'pro';
            }

            return 'part';
        });
    }

    public function getAll()
    {
        return (object) [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'price' => $this->getPrice(),
            'url' => $this->getUrl(),
            'created_at' => $this->getCreatedAt(),
            'thumb' => $this->getThumb(),
            'nb_image' => $this->getNbImage(),
            'placement' => $this->getPlacement(),
            'type' => $this->getType(),
        ];
    }

    /**
     * Return the field's value
     *
     * @param $node
     * @param $defaultValue
     * @param $callback
     * @param string $funcName
     * @param string $funcParam
     *
     * @return mixed
     */
    private function getFieldValue(
        Crawler $node,
        $defaultValue,
        $callback,
        $funcName = 'text',
        $funcParam = ''
    ) {
        if ($node->count()) {
            return $callback($node->$funcName($funcParam));
        }

        return $defaultValue;
    }
}
