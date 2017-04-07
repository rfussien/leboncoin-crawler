<?php

namespace Lbc\Filter;

use Lbc\TestCase;

class SanitizerTest extends TestCase
{
    public function testAnneemodeleSanitizer()
    {
        $this->assertEquals(
            2012,
            AnneemodeleSanitizer::clean("\n\n                  2012\n\n\n")
        );
    }

    public function testCitySanitizer()
    {
        $this->assertEquals(
            "Saint-Martin-d'Aubigny",
            CitySanitizer::clean("\nSaint-Martin-d'Aubigny 50190\n")
        );
    }

    public function testCpSanitizer()
    {
        $this->assertEquals(
            '14400',
            CpSanitizer::clean("\nBayeux 14400\n")
        );
    }

    public function testCylindreeSanitizer()
    {
        $this->assertEquals(
            900,
            CylindreeSanitizer::clean("900 cm3")
        );
    }

    public function testDefaultSanitizer()
    {
        $this->assertEquals(
            'Hello World',
            DefaultSanitizer::clean("\n\n  \nHello World\n   \n")
        );
    }

    public function testKeySanitizer()
    {
        $this->assertEquals(
            'ma_cle',
            KeySanitizer::clean("Ma \nClé")
        );
    }

    public function testKilometrageSanitizer()
    {
        $this->assertEquals(
            54000,
            KilometrageSanitizer::clean("54 000 KM")
        );
    }

    public function testPiecesSanitizer()
    {
        $this->assertEquals(
            11,
            PiecesSanitizer::clean('11')
        );
    }

    public function testPrixSanitizer()
    {
        $this->assertEquals(
            1185000,
            PrixSanitizer::clean('1 185 000 €')
        );
    }

    public function testSurfaceSanitizer()
    {
        $this->assertEquals(
            450,
            SurfaceSanitizer::clean('450 m2')
        );
    }
}
