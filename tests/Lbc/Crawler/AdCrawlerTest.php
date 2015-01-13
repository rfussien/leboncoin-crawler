<?php namespace Lbc\Crawler;

class AdCrawlerTest extends \PHPUnit_Framework_TestCase
{
    protected $adcontent;

    protected $adCrawler;

    protected $url;

    protected $adInfo;

    public function setUp()
    {
        $this->url = 'http://www.leboncoin.fr/ventes_immobilieres/745837877.htm';

        $this->adcontent = file_get_contents(dirname(dirname(__DIR__)) . '/content/ad_753398357.html');

        $this->adCrawler = new AdCrawler($this->adcontent);

        $this->adInfo = [
            'thumbs'      => [
                '0' => 'http://193.164.197.50/thumbs/057/0577f71deaf048e04e74827ee8a1af99fc3bf6e0.jpg',
                '1' => 'http://193.164.196.50/thumbs/ac2/ac21ba0041877475c5887923f0c886f22d7f2f31.jpg',
                '2' => 'http://193.164.196.30/thumbs/33d/33dcf604ed182701e6b88fea4766a1386caa8a95.jpg',
            ],
            'pictures'    => [
                '0' => 'http://193.164.197.50/images/057/0577f71deaf048e04e74827ee8a1af99fc3bf6e0.jpg',
                '1' => 'http://193.164.196.50/images/ac2/ac21ba0041877475c5887923f0c886f22d7f2f31.jpg',
                '2' => 'http://193.164.196.30/images/33d/33dcf604ed182701e6b88fea4766a1386caa8a95.jpg',
            ],
            'title'       => 'Maison 130 m² Fontaine Etoupefour',
            'price'       => 240000,
            'city'        => 'Fontaine-Etoupefour',
            'cp'          => '14790',
            'criterias'   => [
                'type_de_bien'   => 'Maison',
                'pieces'         => '5',
                'surface'        => '130 m2',
                'ges'            => 'Vierge',
                'classe_energie' => 'Vierge',
            ],
            'description' =>
                "AGENCES S'ABSTENIR IMPÉRATIVEMENT . MERCI de respecter\n" .
                "Maison 130 m² environ, Fontaine Etoupefour.\n" .
                "RDC: cuisine ouverte sur salle-salon avec surface totale de " .
                "50 m² ; le tout en parquet chêne massif. Deux Chambres (13 " .
                "m² et 11.5 m²), SDB 8 m² avec douche et baignoire. WC séparés " .
                "Une pièce buanderie 9 m² (placard portes coulissantes)\n" .
                "ETAGE: Deux chambres de 13 m² chacune sous toiture, petite " .
                "salle d'eau lavabo et wc. Grenier à aménager de 35 m². " .
                "Chauffage gaz, chaudière à condensation de 2008 + conduit de " .
                "cheminée existant non utilisé (possible installation de " .
                "cheminée ou poil à bois)\n" .
                "Portail alu motorisé Jardin arboré de 780 m² environ avec " .
                "un abris de jardin, entouré de haies sans aucun vis-à-vis " .
                "avec 2 terrasses en bois à l'avant et à l'arrière de la maison."
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
