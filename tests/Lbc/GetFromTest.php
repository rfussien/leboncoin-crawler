<?php namespace Lbc;

use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Mock;

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
        $this->assertEquals(799, $data['total_ads']);
        $this->assertEquals(23, $data['total_page']);
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

        $url = 'http://www.leboncoin.fr/voitures/offres/basse_normandie/?f=a&th=1&ms=30000&me=70000&fu=2&gb=2';
        $data = $getFrom->search($url, true);

        $expected = (object) [
            'id' => '1046002930',
            'title' => 'Golf 7 vii carat 105 cv dsg',
            'price' => 15490,
            'url' => 'http://www.leboncoin.fr/voitures/1046002930.htm?ca=4_s',
            'created_at' => '2016-11-06 00:11',
            'thumb' => 'http://img5.leboncoin.fr/ad-thumb/e57c3f460fc5f6581e72fbac70c196ca660627fb.jpg',
            'nb_image' => 3,
            'placement' => 'Caen / Calvados',
            'type' => 'part',
        ];

        $this->assertEquals($expected, array_pop($data['ads']));
    }

    public function testGetAdData()
    {
        $response = $this->getResponse(
            dirname(__DIR__) . '/content/ad_897011669.html'
        );

        $mock = new Mock();
        $mock->addResponse($response);
        $mock->addResponse($response);

        $getFrom = new GetFrom();
        $getFrom->getHttpClient()->getEmitter()->attach($mock);
        $getFrom->getHttpClient()->getEmitter()->attach($mock);

        $dataByUrl = $getFrom->ad('http://www.leboncoin.fr/ventes_immobilieres/897011669.htm?ca=3_s');
        $dataById = $getFrom->ad('897011669', 'ventes_immobilieres');

        $this->assertEquals($dataById, $dataByUrl);
        $this->assertEquals('897011669', $dataById['id']);
        $this->assertEquals('ventes_immobilieres', $dataById['category']);
        $this->assertEquals(3, count($dataById['thumbs']));
        $this->assertEquals(3, count($dataById['pictures']));
        $this->assertEquals('Appartement F3 de 71m2,Clermont-fd hyper centre', $dataById['title']);
        $this->assertEquals('63000', $dataById['cp']);
        $this->assertEquals('Clermont-Ferrand', $dataById['city']);
        $this->assertEquals(118000, $dataById['price']);
        $this->assertEquals('Appartement', $dataById['criterias']['type_de_bien']);
        $this->assertEquals('3', $dataById['criterias']['pieces']);
        $this->assertEquals('71 m2', $dataById['criterias']['surface']);
        $this->assertEquals('E (de 36 à 55)', $dataById['criterias']['ges']);
        $this->assertEquals('D (de 151 à 230)', $dataById['criterias']['classe_energie']);
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
