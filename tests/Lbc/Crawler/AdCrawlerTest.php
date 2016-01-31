<?php namespace Lbc\Crawler;

class AdCrawlerTest extends \PHPUnit_Framework_TestCase
{
    protected $adcontent;

    protected $adCrawler;

    protected $url;

    protected $adInfo;

    public function setUp()
    {
        $this->url = 'http://www.leboncoin.fr/ventes_immobilieres/897011669.htm?ca=3_s';

        $this->adcontent = file_get_contents(dirname(dirname(__DIR__)) . '/content/ad_897011669.html');

        $this->adCrawler = new AdCrawler($this->adcontent);

        $this->adInfo = [
            'thumbs'      => [
                '0' => 'http://img6.leboncoin.fr/thumbs/cd2/cd201a9f3952008e989050029bd22bc092ff0d1b.jpg',
                '1' => 'http://img2.leboncoin.fr/thumbs/94c/94c768cf258daea91bf3d40c55cf309088c4fd3f.jpg',
                '2' => 'http://img7.leboncoin.fr/thumbs/e13/e13d7d26816fe5d29d35c998a3905ad4b8e18919.jpg',
            ],
            'pictures'    => [
                '0' => 'http://img6.leboncoin.fr/images/cd2/cd201a9f3952008e989050029bd22bc092ff0d1b.jpg',
                '1' => 'http://img2.leboncoin.fr/images/94c/94c768cf258daea91bf3d40c55cf309088c4fd3f.jpg',
                '2' => 'http://img7.leboncoin.fr/images/e13/e13d7d26816fe5d29d35c998a3905ad4b8e18919.jpg',
            ],
            'title'       => 'Appartement F3 de 71m2,Clermont-fd hyper centre',
            'price'       => 118000,
            'city'        => 'Clermont-Ferrand',
            'cp'          => '63000',
            'criterias'   => [
                'type_de_bien'   => 'Appartement',
                'pieces'         => '3',
                'surface'        => '71 m2',
                'ges'            => 'E (de 36 à 55)',
                'classe_energie' => 'D (de 151 à 230)',
            ],
            'description' =>
                "Quartier galaxie,rue fontgiève à 5 minutes à pied " .
                "du centre-ville, proche de toutes " .
                "commodités,bus,supermarche,école,la banque,la " .
                "poste...\nParticulier à vendre appartement F3 de 71 m2 très " .
                "lumineux,sejour double exposition(sud " .
                "ouest),cuisine equipée,2 chambres,Salle de bain, " .
                "WC séparé et de nombreux rangement.Fenêtres double " .
                "vitrage,volets roulants électrique,très bon etat " .
                "general,1 place de parking couverte et sécurisée " .
                "en rez de chaussée,en face de la gardienne.\nPRIX: 118000 Euros\nTEL: 0671014891 Email: clermaison@yahoo.fr",
        ];
    }

    public function testWeVeGotSomeOfflineContent()
    {
        $this->assertNotNull($this->adcontent);
    }

    public function testWeRetrieveTheThumbs()
    {
        $this->assertEquals(
            $this->adInfo['thumbs'],
            $this->adCrawler->getThumbs()
        );
    }

    public function testWeRetrieveThePictures()
    {
        $this->assertEquals($this->adInfo['pictures'],
            $this->adCrawler->getPictures());
    }

    public function testRetriveTheCommonInfo()
    {
        $expected = [
            'title' => $this->adInfo['title'],
            'price' => $this->adInfo['price'],
            'city'  => $this->adInfo['city'],
            'cp'    => $this->adInfo['cp'],
        ];

        $this->assertEquals($expected, $this->adCrawler->getCommonInfo());
    }

    public function testTheAdDescription()
    {
        $this->assertEquals($this->adInfo['description'],
            $this->adCrawler->getDescription());
    }

    public function testTheAdCriterias()
    {
        $this->assertEquals($this->adInfo['criterias'],
            $this->adCrawler->getCriterias());
    }

    public function testTheFullAdInformation()
    {
        $this->assertEquals($this->adInfo, $this->adCrawler->getAll());
    }
}
