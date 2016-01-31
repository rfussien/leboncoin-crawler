<?php namespace Lbc\Helper;

class UrlNormalizer
{
    /**
     * Test if the url contains a protocol (add http if not)
     *
     * @param string $url
     *
     * @return string
     */
    public static function testUrlProtocol($url)
    {
        if (substr($url, 0, 3) != 'http') {
            $url = "http:" . $url;
        }

        return $url;
    }
}
