<?php namespace Lbc\Parser;

class AdUrlParserTest extends \PHPUnit_Framework_TestCase
{
    protected $url;

    protected $urlParser;

    public function setUp()
    {
        $this->url = 'http://www.leboncoin.fr/voitures/746080950.htm?ca=4_s';

        $this->urlParser = new AdUrlParser($this->url);
    }

    public function testItHasTheRightCategory()
    {
        $this->assertEquals('voitures', $this->urlParser->getCategory());
    }

    public function testItHasTheRightAdId()
    {
        $this->assertEquals('746080950', $this->urlParser->getId());
    }

    public function testItReturnsACleanUrl()
    {
        $this->assertEquals(
            'http://www.leboncoin.fr/voitures/746080950.htm',
            $this->urlParser->getUrl()
        );
    }
}
