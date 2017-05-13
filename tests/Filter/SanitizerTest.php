<?php

namespace Lbc\Filter;

use Lbc\TestCase;

class SanitizerTest extends TestCase
{
    public function testAnneemodeleSanitizer()
    {
        $this->assertEquals(
            2012,
            (new AnneemodeleSanitizer)->clean("\n\n                  2012\n\n\n")
        );
    }

    public function testCitySanitizer()
    {
        $this->assertEquals(
            "Saint-Martin-d'Aubigny",
            (new CitySanitizer)->clean("\nSaint-Martin-d'Aubigny 50190\n")
        );
    }

    public function testCpSanitizer()
    {
        $this->assertEquals(
            '14400',
            (new CpSanitizer)->clean("\nBayeux 14400\n")
        );
    }

    public function testCylindreeSanitizer()
    {
        $this->assertEquals(
            900,
            (new CylindreeSanitizer)->clean("900 cm3")
        );
    }

    public function testDefaultSanitizer()
    {
        $this->assertEquals(
            'Hello World',
            (new DefaultSanitizer)->clean("\n\n  \nHello World\n   \n")
        );
    }

    public function testKeySanitizer()
    {
        $this->assertEquals(
            'ma_cle',
            (new KeySanitizer)->clean("Ma \nClé")
        );
    }

    public function testKilometrageSanitizer()
    {
        $this->assertEquals(
            54000,
            (new KilometrageSanitizer)->clean("54 000 KM")
        );
    }

    public function testPiecesSanitizer()
    {
        $this->assertEquals(
            11,
            (new PiecesSanitizer)->clean('11')
        );
    }

    public function testPrixSanitizer()
    {
        $this->assertEquals(
            1185000,
            (new PrixSanitizer)->clean('1 185 000 €')
        );
    }

    public function testSurfaceSanitizer()
    {
        $this->assertEquals(
            450,
            (new SurfaceSanitizer)->clean('450 m2')
        );
    }
}
