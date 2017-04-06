<?php

namespace Lbc;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $adData = [
        'file' => __DIR__ . '/content/ad_1072097995.html',
        'url'  => 'https://www.leboncoin.fr/ventes_immobilieres/1072097995.htm?ca=4_s',
        'data' => [
            'id'            => '1072097995',
            'category'      => 'ventes_immobilieres',
            'images_thumbs' => [
                '0' => 'https://img0.leboncoin.fr/ad-thumb/6c3962c95d1be2367d8b30f8cc1c04317be61cae.jpg',
                '1' => 'https://img5.leboncoin.fr/ad-thumb/9346546557dc1cf9eafc0249c8f80e27530ec36f.jpg',
                '2' => 'https://img6.leboncoin.fr/ad-thumb/f0e61ab47f008ae101c0ed03e3023d34ee37df5f.jpg',
                '3' => 'https://img4.leboncoin.fr/ad-thumb/60a4a187064407bc792b421189e66f87e1a2425c.jpg',
                '4' => 'https://img5.leboncoin.fr/ad-thumb/d34a4ef9545e60ae88169acbe4858608ba01e8a9.jpg',
            ],
            'images'        => [
                '0' => 'https://img0.leboncoin.fr/ad-image/6c3962c95d1be2367d8b30f8cc1c04317be61cae.jpg',
                '1' => 'https://img5.leboncoin.fr/ad-image/9346546557dc1cf9eafc0249c8f80e27530ec36f.jpg',
                '2' => 'https://img6.leboncoin.fr/ad-large/f0e61ab47f008ae101c0ed03e3023d34ee37df5f.jpg',
                '3' => 'https://img4.leboncoin.fr/ad-image/60a4a187064407bc792b421189e66f87e1a2425c.jpg',
                '4' => 'https://img5.leboncoin.fr/ad-image/d34a4ef9545e60ae88169acbe4858608ba01e8a9.jpg',
            ],
            'properties'    => [
                'title'          => 'Maison 11 pièces 450 m²',
                'price'          => 1185000,
                'city'           => 'Bayeux',
                'cp'             => '14400',
                'type_de_bien'   => 'Maison',
                'pieces'         => '11',
                'surface'        => '450 m2',
                'reference'      => '394348',
                'ges'            => 'C (de 11 à 20)',
                'classe_energie' => 'C (de 91 à 150)',
            ],
            'description'   =>
                "Vente Maison/villa 11 piècesI@D France - Estelle ARRECGROS " .
                "(07 77 96 03 60) vous propose : BIEN D'EXCEPTION ! Magnifique" .
                " château du 19 ème siècle édifié sur un parc arboré et clos de" .
                " 7000 m² environ. Cette propriété restaurée de 450 m² environ a " .
                "gardé tous ses éléments anciens qui en font un bien rare sur " .
                "le marché. Son hall d'entrée présentant un somptueux escalier " .
                "dessert de belles salles de réception. Plusieurs chambres et " .
                "suites se trouvent au premier étage. Les pièces sont lumineuses " .
                "et uniques. Ce château comprend aussi un pavillon de chasse, une " .
                "orangeraie, une chapelle ainsi qu'un grand garage.Honoraires " .
                "d'agence à la charge du vendeur.Information d'affichage énergétique " .
                "sur ce bien : DPE C indice 96.1 et GES C indice 12.5. La présente " .
                "annonce immobilière a été rédigée sous la responsabilité éditoriale " .
                "de Mlle Estelle ARRECGROS (ID 21029), Agent Commercial mandataire " .
                "en immobilier immatriculé au Registre Spécial des Agents Commerciaux " .
                "(RSAC) du Tribunal de Commerce de Caen sous le numéro " .
                "823562178Référence annonce : 394348",
        ],
    ];

    protected $searchData = [
        'file' => __DIR__ . '/content/search_result.html',
        'url'  =>
            'https://www.leboncoin.fr/voitures/offres/basse_normandie/' .
            '?o=2&ms=30000&me=100000&fu=2&gb=2&location=Caen%2014000',
    ];

    protected $searchData2 = [
        'file' => __DIR__ . '/content/search_result2.html',
        'url'  => 'https://www.leboncoin.fr/telephonie/offres/basse_normandie/?f=a&th=1&q=iphone',
    ];
}
