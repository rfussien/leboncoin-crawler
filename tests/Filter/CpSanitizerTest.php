<?php

namespace Lbc\Filter;

use Lbc\TestCase;

class CpSanitizerTest extends TestCase
{
    public function testKeyFilter()
    {
        $this->assertEquals(
            '14400',
            CpSanitizer::clean("\nBayeux 14400\n")
        );
    }
}
