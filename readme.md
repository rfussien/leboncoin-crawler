Crawler for leboncoin.fr
========================

[![Build Status](https://api.travis-ci.org/rfussien/leboncoin-crawler.svg)](https://travis-ci.org/rfussien/leboncoin-crawler/)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rfussien/leboncoin-crawler/badges/quality-score.png)](https://scrutinizer-ci.com/g/rfussien/leboncoin-crawler/)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b9916e36-30d9-4d16-ba5c-c1077b45b37e/mini.png)](https://insight.sensiolabs.com/projects/b9916e36-30d9-4d16-ba5c-c1077b45b37e)
[![Latest Stable Version](https://poser.pugx.org/rfussien/leboncoin-crawler/v/stable.svg)](https://packagist.org/packages/rfussien/leboncoin-crawler)
[![Latest Unstable Version](https://poser.pugx.org/rfussien/leboncoin-crawler/v/unstable.svg)](https://packagist.org/packages/rfussien/leboncoin-crawler)
[![License](https://poser.pugx.org/rfussien/leboncoin-crawler/license.svg)](https://packagist.org/packages/rfussien/leboncoin-crawler)

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

- PHP 5.6 | 7 | 7.1, HHVM
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
```json
{
    "page": 2,
    "links": {
        "current": "http://www.leboncoin.fr/ventes_immobilieres/offres/basse_normandie/calvados/?pe=11&sqs=10&ros=5&ret=1&f=p&o=2",
        "previous": "http://www.leboncoin.fr/ventes_immobilieres/offres/basse_normandie/calvados/?pe=11&sqs=10&ros=5&ret=1&f=p&o=1",
        "next": "http://www.leboncoin.fr/ventes_immobilieres/offres/basse_normandie/calvados/?pe=11&sqs=10&ros=5&ret=1&f=p&o=3"
    },
    "total_ads": 604,
    "total_page": 18,
    "category": "ventes_immobilieres",
    "location": null,
    "search_area": "basse_normandie",
    "sort_by": "date",
    "type": "part",
    "ads": {
        "602701721": {
            "id": "602701721",
            "title": "Maison F7 à EVRECY",
            "price": 200000,
            "url": "http://www.leboncoin.fr/ventes_immobilieres/602701721.htm?ca=4_s",
            "created_at": "2015-01-10 19:01",
            "thumb": "http://193.164.197.40/thumbs/808/808ae4f91c5bf1871b96f16bccb3751eeb0baec4.jpg",
            "nb_image": 3,
            "placement": "Evrecy / Calvados",
            "type": "part"
        },
        [...]
        "755560430": {
            "id": "755560430",
            "title": "Maison Atypique ( Esprit Loft ) 145 m2",
            "price": 243000,
            "url": "http://www.leboncoin.fr/ventes_immobilieres/755560430.htm?ca=4_s",
            "created_at": "2015-01-11 19:01",
            "thumb": "http://193.164.196.60/thumbs/aa3/aa336ba634f7e5f43b6c016358afa2510e42aa0b.jpg",
            "nb_image": 3,
            "placement": "Caen / Calvados",
            "type": "part"
        }
    }
}
```

### Get the structured data from an ad

```php
(new Lbc\GetFrom)->ad('<ad_url>');
// or
(new Lbc\GetFrom)->ad('<ad_id>', '<ad_category>');
```

*example of output*:
```json
{
    "id": "602701721",
    "category": "ventes_immobilieres",
    "thumbs": [
        "http://193.164.197.40/thumbs/808/808ae4f91c5bf1871b96f16bccb3751eeb0baec4.jpg",
        "http://193.164.196.60/thumbs/1b4/1b40871304534d25c99c7b3baeda07c16c8b48cd.jpg",
        "http://193.164.196.30/thumbs/152/15251eb4128758c6d0c44523b6733ee9d5ea3749.jpg"
    ],
    "pictures": [
        "http://193.164.197.40/images/808/808ae4f91c5bf1871b96f16bccb3751eeb0baec4.jpg",
        "http://193.164.196.60/images/1b4/1b40871304534d25c99c7b3baeda07c16c8b48cd.jpg",
        "http://193.164.196.30/images/152/15251eb4128758c6d0c44523b6733ee9d5ea3749.jpg"
    ],
    "title": "Maison F7 à EVRECY",
    "cp": "14210",
    "city": "Evrecy",
    "price": 200000,
    "criterias": {
        "type_de_bien": "Maison",
        "pieces": "7",
        "surface": "140 m2",
        "ges": "F (de 56 à 80)",
        "classe_energie": "D (de 151 à 230)"
    },
    "description": "Baisse de prix pour une maison à [...] sur un terrain de 576 m². AGENCE S'ABSTENIR."
}
```

There are a bunch of features if you digg a bit in the sources.


## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email me (<mailto:remi.fussien@gmail.com>) instead of using the issue tracker.

## Credits

- [Rémi FUSSIEN][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
