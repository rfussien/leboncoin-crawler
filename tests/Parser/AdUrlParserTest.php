<?php

namespace Lbc\Parser;

use Lbc\TestCase;

class AdUrlParserTest extends TestCase
{
    protected $url;

    protected $urlParser;

    public function setUp()
    {
        $this->urlParser = new AdUrlParser($this->adData['url']);
    }

    public function testItHasTheRightCategory()
    {
        $this->assertEquals('ventes_immobilieres', $this->urlParser->getCategory());
    }

    public function testItHasTheRightAdId()
    {
        $this->assertEquals('1072097995', $this->urlParser->getId());
    }

    public function testItReturnsACleanUrl()
    {
        $this->assertEquals(
            'https://www.leboncoin.fr/ventes_immobilieres/1072097995.htm',
            (string) $this->urlParser
        );
    }
}
