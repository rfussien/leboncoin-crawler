Crawler for leboncoin.fr
========================

[![Build Status](https://api.travis-ci.org/rfussien/leboncoin-crawler.svg)](https://travis-ci.org/rfussien/leboncoin-crawler/)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rfussien/leboncoin-crawler/badges/quality-score.png)](https://scrutinizer-ci.com/g/rfussien/leboncoin-crawler/)
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

## Installation

```bash
composer install "rfussien/leboncoin-crawler"
```

## Usage

**Super easy !!!**

### Get the structured data from a search result page

```php
Lbc\GetFrom::search('<search_result_url>');
```

### Get the structured data from an ad

```php
Lbc\GetFrom::ad('<ad_url>');
// or
Lbc\GetFrom::ad('<ad_id>', '<ad_category>');
```

There are a bunch of features if you digg a bit in the sources.
