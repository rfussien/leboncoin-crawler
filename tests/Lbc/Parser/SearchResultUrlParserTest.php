<?php namespace Lbc\Parser;

class SearchResultUrlParserTest extends \PHPUnit_Framework_TestCase
{
    public function testPartialUrlWorks()
    {
        $urlParser = new SearchResultUrlParser('/voitures/offres/basse_normandie/');

        $this->assertEquals(
            'http://www.leboncoin.fr/voitures/offres/basse_normandie/?o=1',
            (string) $urlParser->current()
        );
    }

    public function testUselessQueryParamsAreRemoved()
    {
        $urlParser = new SearchResultUrlParser('voitures/offres/basse_normandie/?th=1');

        $this->assertEmpty($urlParser->current()->getQuery()['th']);
    }

    public function testItHasADefaultPage() {
        $urlParser = new SearchResultUrlParser('voitures/offres/basse_normandie/');

        $this->assertNotEmpty($urlParser->current()->getQuery()['o']);
    }

    public function testItHasAPreviousPage()
    {
        $urlParser = new SearchResultUrlParser('voitures/offres/basse_normandie/?o=3', 6);

        $this->assertEquals(2, $urlParser->previous()->getQuery()['o']);
    }

    public function testItHasANextPage()
    {
        $urlParser = new SearchResultUrlParser('voitures/offres/basse_normandie/?o=3', 6);

        $this->assertEquals(4, $urlParser->next()->getQuery()['o']);
    }

    public function testThereIsNoPreviousPageBeforeTheFirstOne()
    {
        $urlParser = new SearchResultUrlParser('voitures/offres/basse_normandie/');

        $this->isNull($urlParser->previous());
    }

    public function testThereIsNoNextPageAfterTheLastOne()
    {
        $urlParser = new SearchResultUrlParser('voitures/offres/basse_normandie/?o=3', 3);

        $this->isNull($urlParser->next());
    }

    public function testTheNavReturnsWhatItShould()
    {
        $urlParser = new SearchResultUrlParser('voitures/offres/basse_normandie/?o=2', 3);

        $expected = [
            'page' => 2,
            'links' => [
                'previous' => 'http://www.leboncoin.fr/voitures/offres/basse_normandie/?o=1',
                'current'  => 'http://www.leboncoin.fr/voitures/offres/basse_normandie/?o=2',
                'next'     => 'http://www.leboncoin.fr/voitures/offres/basse_normandie/?o=3',
            ]
        ];

        $this->assertEquals($expected, $urlParser->getNav());

        $urlParser = new SearchResultUrlParser('voitures/offres/basse_normandie/?o=1', 3);

        $expected = [
            'page' => 1,
            'links' => [
                'previous' => '',
                'current'  => 'http://www.leboncoin.fr/voitures/offres/basse_normandie/?o=1',
                'next'     => 'http://www.leboncoin.fr/voitures/offres/basse_normandie/?o=2',
            ]
        ];

        $this->assertEquals($expected, $urlParser->getNav());
    }

    public function testItReturnTheCorrectCategory()
    {
        $urlParser = new SearchResultUrlParser('voitures/offres/basse_normandie/');

        $this->assertEquals('voitures', $urlParser->getCategory());
    }

    public function testItReturnNullWhenNoCategoryFound()
    {
        $urlParser = new SearchResultUrlParser('annonces/offres/basse_normandie/');

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
        $urlParser = new SearchResultUrlParser('voitures/offres/basse_normandie/?location=Caen%2014000%2CVerson%2014790');

        $this->assertEquals('Caen 14000,Verson 14790', $urlParser->getLocation());
    }

    public function testGetTheRightAdsType()
    {
        $urlParser = new SearchResultUrlParser('voitures/offres/basse_normandie/');
        $this->assertEquals('all', $urlParser->getType());

        $urlParser = new SearchResultUrlParser('voitures/offres/basse_normandie/?f=a');
        $this->assertEquals('all', $urlParser->getType());

        $urlParser = new SearchResultUrlParser('voitures/offres/basse_normandie/?f=p');
        $this->assertEquals('part', $urlParser->getType());

        $urlParser = new SearchResultUrlParser('voitures/offres/basse_normandie/?f=c');
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
