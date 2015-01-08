Crawler for leboncoin.fr
========================

[![Build Status](https://travis-ci.org/rfussien/leboncoin-crawler.svg)](https://travis-ci.org/rfussien/leboncoin-crawler)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rfussien/leboncoin-crawler/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rfussien/leboncoin-crawler/?branch=master)

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

And the third and last problem is that the you are forced to use selected values
with some criterias. For example, when I was looking for a motorcycle, I was
looking for those with a bigger engine than 1200cc. The fact that the biggest
value available in the input is 1000cc and because there are tons of ads with
1000cc motorcycle made search much more complicated. I did send a mail to ask
for an additional value, but I didn't get any answer (which I didn't expect
anyway). So I had to change the value in the query string every single request...
What a waste of time...

## Installation

I'll write how it works later... But you can have a look to the unit test to
get it.
