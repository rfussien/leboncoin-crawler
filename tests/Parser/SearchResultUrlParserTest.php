<?php

namespace Lbc\Parser;

use Lbc\TestCase;

class SearchResultUrlParserTest extends TestCase
{
    public function testPartialUrlWorks()
    {
        $urlParser = new SearchResultUrlParser(
            'https://www.leboncoin.fr/voitures/offres/basse_normandie/'
        );

        $this->assertEquals(
            'https://www.leboncoin.fr/voitures/offres/basse_normandie/?o=1',
            (string)$urlParser->current()
        );
    }

    public function testUselessQueryParamsAreRemoved()
    {
        $urlParser = new SearchResultUrlParser(
            'https://www.leboncoin.fr/voitures/offres/basse_normandie/?th=1'
        );

        $this->assertEmpty($urlParser->current()->query->getValue('th'));
    }

    public function testItHasADefaultPage()
    {
        $urlParser = new SearchResultUrlParser(
            'https://www.leboncoin.fr/voitures/offres/basse_normandie/'
        );

        $this->assertNotEmpty($urlParser->current()->query->getValue('o'));
    }

    public function testItHasAPreviousPage()
    {
        $urlParser = new SearchResultUrlParser(
            'https://www.leboncoin.fr/voitures/offres/basse_normandie/?o=3',
            6
        );

        $this->assertEquals(2, $urlParser->previous()->query->getValue('o'));
    }

    public function testItHasANextPage()
    {
        $urlParser = new SearchResultUrlParser(
            'https://www.leboncoin.fr/voitures/offres/basse_normandie/?o=3',
            6
        );

        $this->assertEquals(4, $urlParser->next()->query->getValue('o'));
    }

    public function testThereIsNoPreviousPageBeforeTheFirstOne()
    {
        $urlParser = new SearchResultUrlParser(
            'https://www.leboncoin.fr/voitures/offres/basse_normandie/'
        );

        $this->isNull($urlParser->previous());
    }

    public function testThereIsNoNextPageAfterTheLastOne()
    {
        $urlParser = new SearchResultUrlParser(
            'https://www.leboncoin.fr/voitures/offres/basse_normandie/?o=3',
            3
        );

        $this->isNull($urlParser->next());
    }

    public function testTheNavReturnsWhatItShould()
    {
        $urlParser = new SearchResultUrlParser(
            'https://www.leboncoin.fr/voitures/offres/basse_normandie/?o=2',
            3
        );

        $expected = [
            'page'  => 2,
            'links' => [
                'previous' => 'https://www.leboncoin.fr/voitures/offres/basse_normandie/?o=1',
                'current'  => 'https://www.leboncoin.fr/voitures/offres/basse_normandie/?o=2',
                'next'     => 'https://www.leboncoin.fr/voitures/offres/basse_normandie/?o=3',
            ]
        ];

        $this->assertEquals($expected, $urlParser->getNav());

        $urlParser = new SearchResultUrlParser(
            'https://www.leboncoin.fr/voitures/offres/basse_normandie/?o=1',
            3
        );

        $expected = [
            'page'  => 1,
            'links' => [
                'previous' => '',
                'current'  => 'https://www.leboncoin.fr/voitures/offres/basse_normandie/?o=1',
                'next'     => 'https://www.leboncoin.fr/voitures/offres/basse_normandie/?o=2',
            ]
        ];

        $this->assertEquals($expected, $urlParser->getNav());
    }

    public function testItReturnTheCorrectCategory()
    {
        $urlParser = new SearchResultUrlParser(
            'https://www.leboncoin.fr/voitures/offres/basse_normandie/'
        );

        $this->assertEquals('voitures', $urlParser->getCategory());
    }

    public function testItReturnNullWhenNoCategoryFound()
    {
        $urlParser = new SearchResultUrlParser(
            'https://www.leboncoin.fr/annonces/offres/basse_normandie/'
        );

        $this->isNull($urlParser->getCategory());
    }

    public function testItReturnTheCorrectSearchArea()
    {
        $urlParser = new SearchResultUrlParser('voitures/offres/basse_normandie/');
        $this->assertEquals('basse_normandie', $urlParser->getSearchArea());

        $urlParser = new SearchResultUrlParser('voitures/offres/basse_normandie/bonnes_affaires');
        $this->assertEquals('regions voisines', $urlParser->getSearchArea());

        $urlParser = new SearchResultUrlParser('voitures/offres/basse_normandie/occasions');
        $this->assertEquals('toute la france', $urlParser->getSearchArea());
    }

    public function testItReturnNullWhenNoLocation()
    {
        $urlParser = new SearchResultUrlParser('voitures/offres/basse_normandie/');

        $this->isNull($urlParser->getLocation());
    }

    public function testItReturnTheCorrectLocation()
    {
        $urlParser = new SearchResultUrlParser(
            'https://www.leboncoin.fr/voitures/offres/basse_normandie/?location=Caen%2014000%2CVerson%2014790'
        );

        $this->assertEquals(
            'Caen 14000,Verson 14790',
            $urlParser->getLocation()
        );
    }

    public function testGetTheRightAdsType()
    {
        $urlParser = new SearchResultUrlParser(
            'https://www.leboncoin.fr/voitures/offres/basse_normandie/'
        );
        $this->assertEquals('all', $urlParser->getType());

        $urlParser = new SearchResultUrlParser(
            'https://www.leboncoin.fr/voitures/offres/basse_normandie/?f=a'
        );
        $this->assertEquals('all', $urlParser->getType());

        $urlParser = new SearchResultUrlParser(
            'https://www.leboncoin.fr/voitures/offres/basse_normandie/?f=p'
        );
        $this->assertEquals('part', $urlParser->getType());

        $urlParser = new SearchResultUrlParser(
            'https://www.leboncoin.fr/voitures/offres/basse_normandie/?f=c'
        );
        $this->assertEquals('pro', $urlParser->getType());
    }

    public function testGetTheSortingType()
    {
        $urlParser = new SearchResultUrlParser('voitures/offres/basse_normandie/');
        $this->assertEquals('date', $urlParser->getSortType());

        $urlParser = new SearchResultUrlParser('voitures/offres/basse_normandie/?sp=0');
        $this->assertEquals('date', $urlParser->getSortType());

        $urlParser = new SearchResultUrlParser('voitures/offres/basse_normandie/?sp=1');
        $this->assertEquals('price', $urlParser->getSortType());
    }
}
