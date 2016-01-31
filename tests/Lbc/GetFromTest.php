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
        $this->assertEquals(690, $data['total_ads']);
        $this->assertEquals(20, $data['total_page']);
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

        $expected = (object)[
            'id'         => '917789397',
            'title'      => 'Volvo xc90 r design',
            'price'      => 30000,
            'url'        => 'http://www.leboncoin.fr/voitures/917789397.htm?ca=4_s',
            'created_at' => SearchResultDateTimeParser::toDt("Hier", "18:01"),
            'thumb'      => 'http://img6.leboncoin.fr/thumbs/907/90783d4040062193c703d48e4929f95c15bf1233.jpg',
            'nb_image'   => 3,
            'placement'  => 'Saint-Hilaire-du-Harcouët / Manche',
            'type'       => 'part',
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
