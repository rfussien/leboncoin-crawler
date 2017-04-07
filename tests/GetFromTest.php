<?php

namespace Lbc;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;

class GetFromTest extends TestCase
{
    public function testGetHttpClient()
    {
        $client = new Client();

        $getFrom = new GetFrom($client);

        $this->assertSame($client, $getFrom->getHttpClient());
    }

    public function testGetTheSearchResultData()
    {
        $response = $this->getResponse($this->searchData['file']);

        $mock = new MockHandler([$response]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $getFrom = new GetFrom($client);

        $data = $getFrom->search($this->searchData['url']);

        $this->assertEquals(2, $data['page']);
        $this->assertEquals(73, $data['total_ads']);
        $this->assertEquals(3, $data['total_page']);
        $this->assertEquals(35, $data['ads_per_page']);
        $this->assertEquals('voitures', $data['category']);
        $this->assertEquals('basse_normandie', $data['search_area']);
        $this->assertEquals('date', $data['sort_by']);
        $this->assertEquals('all', $data['type']);
        $this->assertCount(35, $data['ads']);
    }

    public function testGetTheDetailedAdInTheSearchResult()
    {
        $response = $this->getResponse($this->searchData['file']);

        $mock = new MockHandler([$response]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $getFrom = new GetFrom($client);

        $data = $getFrom->search($this->searchData['url'], true);

        $expected = (object)[
            'id'            => '1094424477',
            'titre'         => 'BMW 118dA Sport Line Toit ouvrant',
            'is_pro'        => true,
            'prix'          => 21890,
            'url'           => 'https://www.leboncoin.fr/voitures/1094424477.htm',
            'created_at'    => '2017-02-17',
            'images_thumbs' => 'https://img3.leboncoin.fr/ad-thumb/2b01dcbc684ff4f619e7733bbcbf14d2d71d77c3.jpg',
            'nb_image'      => 5,
            'placement'     => 'Caen / Calvados',
        ];

        $this->assertEquals($expected, array_pop($data['ads']));
    }

    public function testGetAdDataByUrl()
    {
        $response = $this->getResponse(
            __DIR__ . '/content/ad_1072097995.html'
        );

        $mock = new MockHandler([$response]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $getFrom = new GetFrom($client);

        $data = $getFrom->ad('https://www.leboncoin.fr/ventes_immobilieres/1072097995.htm');

        $this->assertEquals($this->adData['data'], $data);
    }

    public function testGetAdDataById()
    {
        $response = $this->getResponse(
            __DIR__ . '/content/ad_1072097995.html'
        );

        $mock = new MockHandler([$response]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $getFrom = new GetFrom($client);

        $data = $getFrom->ad('1072097995', 'ventes_immobilieres');

        $this->assertEquals($this->adData['data'], $data);
    }

    private function getResponse($file)
    {
        return new Response(200, [], new Stream(fopen($file, 'rb')));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Bad number of argument
     */
    public function testAnExceptionIsThrownWhenBadNumberOfArgumentAreUsed()
    {
        $getFrom = new GetFrom();
        $getFrom->ad(1, 2, 3);
    }
}
