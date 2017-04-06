<?php

namespace Lbc\Filter;

use Lbc\TestCase;

class PriceSanitizerTest extends TestCase
{
    public function testKeyFilter()
    {
        $this->assertEquals(
            1185000,
            PriceSanitizer::clean('1 185 000 €')
        );
    }
}
