<?php

namespace Lbc\Filter;

use Lbc\TestCase;

class SanitizerTest extends TestCase
{
    public function testKeySanitizer()
    {
        $this->assertEquals(
            'my_key',
            KeySanitizer::clean("My \nkey")
        );
    }

    public function testPrixSanitizer()
    {
        $this->assertEquals(
            1185000,
            PrixSanitizer::clean('1 185 000 €')
        );
    }

    public function testCpSanitizer()
    {
        $this->assertEquals(
            '14400',
            CpSanitizer::clean("\nBayeux 14400\n")
        );
    }
}
