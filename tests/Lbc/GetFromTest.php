<?php namespace Lbc;

use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Mock;
use Lbc\Parser\SearchResultDateTimeParser;

class GetFromTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTheSearchResultData()
    {
        $response = $this->getResponse(
            dirname(__DIR__) . '/content/search_result.html'
        );

        $mock = new Mock();
        $mock->addResponse($response);

        $getFrom = new GetFrom();
        $getFrom->getHttpClient()->getEmitter()->attach($mock);

        $url = 'http://www.leboncoin.fr/voitures/offres/basse_normandie/?f=a&th=1&ms=30000&me=100000&fu=2&gb=2';
        $data = $getFrom->search($url);

        $this->assertEquals(1, $data['page']);
        $this->assertEquals(650, $data['total_ads']);
        $this->assertEquals(19, $data['total_page']);
        $this->assertEquals('voitures', $data['category']);
        $this->assertEquals('basse_normandie', $data['search_area']);
        $this->assertEquals('date', $data['sort_by']);
        $this->assertEquals('all', $data['type']);
        $this->assertEquals(35, count($data['ads']));
    }

    public function testGetTheDetailedAdInTheSearchResult()
    {
        $response = $this->getResponse(
            dirname(__DIR__) . '/content/search_result.html'
        );

        $mock = new Mock();
        $mock->addResponse($response);

        $getFrom = new GetFrom();
        $getFrom->getHttpClient()->getEmitter()->attach($mock);

        $url = 'http://www.leboncoin.fr/voitures/offres/basse_normandie/?f=a&th=1&ms=30000&me=100000&fu=2&gb=2';
        $data = $getFrom->search($url, true);

        $expected = (object)[
            'id'         => '746080950',
            'title'      => 'Volkswagen touareg 3.0 v6 tdi 240 carat edition',
            'price'      => 24200,
            'url'        => 'http://www.leboncoin.fr/voitures/746080950.htm?ca=4_s',
            'created_at' => SearchResultDateTimeParser::toDt("Aujourd'hui", "11:01"),
            'thumb'      => 'http://193.164.196.50/thumbs/aa5/aa55af1f945f02f8452fcfb2061d93b2d380eefd.jpg',
            'nb_image'   => 7,
            'placement'  => 'Caen / Calvados',
            'type'       => 'part'
        ];

        $this->assertEquals($expected, array_pop($data['ads']));
    }

    public function testGetAdData()
    {
        $response = $this->getResponse(
            dirname(__DIR__) . '/content/ad_753398357.html'
        );

        $mock = new Mock();
        $mock->addResponse($response);
        $mock->addResponse($response);

        $getFrom = new GetFrom();
        $getFrom->getHttpClient()->getEmitter()->attach($mock);
        $getFrom->getHttpClient()->getEmitter()->attach($mock);

        $dataByUrl = $getFrom->ad('http://www.leboncoin.fr/ventes_immobilieres/745837877.htm');
        $dataById = $getFrom->ad('745837877', 'ventes_immobilieres');

        $this->assertEquals($dataById, $dataByUrl);
        $this->assertEquals(3, count($dataById['thumbs']));
        $this->assertEquals(3, count($dataById['pictures']));
        $this->assertEquals('Maison 130 m² Fontaine Etoupefour',
            $dataById['title']);
        $this->assertEquals('14790', $dataById['cp']);
        $this->assertEquals('Fontaine-Etoupefour', $dataById['city']);
        $this->assertEquals(240000, $dataById['price']);
        $this->assertEquals('Maison', $dataById['criterias']['type_de_bien']);
        $this->assertEquals('5', $dataById['criterias']['pieces']);
        $this->assertEquals('130 m2', $dataById['criterias']['surface']);
        $this->assertEquals('Vierge', $dataById['criterias']['ges']);
        $this->assertEquals('Vierge', $dataById['criterias']['classe_energie']);
        $this->assertNotEmpty($dataById['description']);
    }

    private function getResponse($file)
    {
        $response = new Response(200);
        $response->setBody(Stream::factory(fopen($file, 'r')));

        return $response;
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Bad number of argument
     */
    public function testAnExceptionIsThrownWhenBadNumberOfArgumentAreUsed()
    {
        $getFrom = new GetFrom();
        $getFrom->ad(1, 2, 3);
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testAnExceptionIsThrownWhenBadMethodAreCalled()
    {
        $getFrom = new GetFrom();
        $getFrom->thisMethodCertainlyDoesntExists();
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testAnExceptionIsThrownWhenBadStaticMethodAreCalled()
    {
        GetFrom::thisMethodCertainlyDoesntExists();
    }
}
