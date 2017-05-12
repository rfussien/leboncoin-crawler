Crawler for leboncoin.fr
========================

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

This is a small crawler package for the site [leboncoin.fr](http://www.leboncoin.fr).

## Why ?

[leboncoin.fr](http://www.leboncoin.fr) is one of the most famous classified ads
website in france. Whatever what you're looking for, it is probably there. It has
a lots of ads and is very fast and simple to use.

However, the first problem comes when you need to exploit the search results in
a way that the site can't help you. In fact, the search results page is
pretty poor in terms of data.
For exemple, it'd be so cool to get the mileage when I'm looking for a car or
the surface when I'm looking for a flat.

The second problem is that saving a search is really a pain on the actual site.
All the searches you want to save give one single result page. That's pretty dumb,
but anyway.

And the third and last problem is that you are forced to use selected values
with some criterias. For example, when I was looking for a motorcycle, I was
looking for those with a bigger engine than 1200cc. The fact that the biggest
value available in the input is 1000cc and because there are tons of ads with
1000cc motorcycle, it made search much more complicated. I did send an email to
ask for an additional value, but I didn't get any answer (which I didn't expect
anyway). So I had to change the value in the query string every single request...
What a waste of time...

So for all those little reasons, I decided to write my good old web scraper to
be able to extract the data from the site to anywhere (a DB, an array, a json,
an api, who knows...).

## Requirements

- PHP 7
- [optional] PHPUnit to execute the test suite

## Install

```bash
$ composer require rfussien/leboncoin-crawler
```

## Usage

**Super easy !!!**

### Get the structured data from a search result page

```php
(new Lbc\GetFrom)->search('<search_result_url>');
// or with detailed ads
(new Lbc\GetFrom)->search('<search_result_url>', true);
```
*example of output*:
```php
[
  'page' => 2,
  'links' => [
    'current' => 'https://www.leboncoin.fr/ventes_immobilieres/offres/basse_normandie/?o=2&sqs=12&ret=1&location=Caen%2014000',
    'previous' => 'https://www.leboncoin.fr/ventes_immobilieres/offres/basse_normandie/?o=1&sqs=12&ret=1&location=Caen%2014000',
    'next' => 'https://www.leboncoin.fr/ventes_immobilieres/offres/basse_normandie/?o=3&sqs=12&ret=1&location=Caen%2014000',
  ],
  'total_ads' => 466,
  'total_page' => 14,
  'ads_per_page' => 35,
  'category' => 'ventes_immobilieres',
  'location' => 'Caen 14000',
  'search_area' => 'basse_normandie',
  'sort_by' => 'date',
  'type' => 'all',
  'ads' => [
    1117890265 => [
      'id' => '1117890265',
      'titre' => 'Maison 7 pièces 243 m²',
      'is_pro' => true,
      'prix' => 490000,
      'url' => 'https://www.leboncoin.fr/ventes_immobilieres/1117890265.htm',
      'created_at' => '2017-04-06',
      'images_thumbs' => 'https://img1.leboncoin.fr/ad-thumb/fdf29ab66506b52f5768c509cbd4c9940035b220.jpg',
      'nb_image' => '10',
      'placement' => 'Caen / Calvados',
    ],
    [...],
    1116940130 => [
      'id' => '1116940130',
      'titre' => 'Maison de ville 5 pièces 121 m²',
      'is_pro' => true,
      'prix' => 338000,
      'url' => 'https://www.leboncoin.fr/ventes_immobilieres/1116940130.htm',
      'created_at' => '2017-04-04',
      'images_thumbs' => 'https://img2.leboncoin.fr/ad-thumb/2bb09136b010d9009f0d5542c8699ede3f6bedfd.jpg',
      'nb_image' => '4',
      'placement' => 'Caen / Calvados',
    ],
  ],
]
```

### Get the structured data from an ad

```php
(new Lbc\GetFrom)->ad('<ad_url>');
// or
(new Lbc\GetFrom)->ad('<ad_id>', '<ad_category>');
```

*example of output*:
```php
[
    'id'            => '1072097995',
    'category'      => 'ventes_immobilieres',
    'images_thumbs' => [
        0 => 'https://img0.leboncoin.fr/ad-thumb/6c3962c95d1be2367d8b30f8cc1c04317be61cae.jpg',
        1 => 'https://img5.leboncoin.fr/ad-thumb/9346546557dc1cf9eafc0249c8f80e27530ec36f.jpg',
        2 => 'https://img6.leboncoin.fr/ad-thumb/f0e61ab47f008ae101c0ed03e3023d34ee37df5f.jpg',
        3 => 'https://img4.leboncoin.fr/ad-thumb/60a4a187064407bc792b421189e66f87e1a2425c.jpg',
        4 => 'https://img5.leboncoin.fr/ad-thumb/d34a4ef9545e60ae88169acbe4858608ba01e8a9.jpg',
    ],
    'images'        => [
        0 => 'https://img0.leboncoin.fr/ad-image/6c3962c95d1be2367d8b30f8cc1c04317be61cae.jpg',
        1 => 'https://img5.leboncoin.fr/ad-image/9346546557dc1cf9eafc0249c8f80e27530ec36f.jpg',
        2 => 'https://img6.leboncoin.fr/ad-large/f0e61ab47f008ae101c0ed03e3023d34ee37df5f.jpg',
        3 => 'https://img4.leboncoin.fr/ad-image/60a4a187064407bc792b421189e66f87e1a2425c.jpg',
        4 => 'https://img5.leboncoin.fr/ad-image/d34a4ef9545e60ae88169acbe4858608ba01e8a9.jpg',
    ],
    'properties'    => [
        'titre'          => 'Maison 11 pièces 450 m²',
        'created_at'     => '2017-02-18',
        'is_pro'         => 1,
        'prix'           => 1185000,
        'ville'          => 'Bayeux',
        'cp'             => '14400',
        'type_de_bien'   => 'Maison',
        'pieces'         => 11,
        'surface'        => 450,
        'reference'      => '394348',
        'ges'            => 'C (de 11 à 20)',
        'classe_energie' => 'C (de 91 à 150)',
    ],
    'description'   => 'Vente Maison/villa 11 piècesI@D France - [...]3562178Référence annonce : 394348',
]
```

There are a bunch of features if you digg a bit in the sources.


## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email me (<remi.fussien@gmail.com>) instead of using the issue tracker.

## Credits

- [Rémi FUSSIEN][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.


[ico-version]: https://img.shields.io/packagist/v/rfussien/leboncoin-crawler.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/rfussien/leboncoin-crawler/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/rfussien/leboncoin-crawler.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/rfussien/leboncoin-crawler.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rfussien/leboncoin-crawler.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/rfussien/leboncoin-crawler
[link-travis]: https://travis-ci.org/rfussien/leboncoin-crawler
[link-scrutinizer]: https://scrutinizer-ci.com/g/rfussien/leboncoin-crawler/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/rfussien/leboncoin-crawler
[link-downloads]: https://packagist.org/packages/rfussien/leboncoin-crawler
[link-author]: https://github.com/:author_username
[link-contributors]: ../../contributors
