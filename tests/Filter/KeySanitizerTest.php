<?php

namespace Lbc\Filter;

use Lbc\TestCase;

class KeySanitizerTest extends TestCase
{
    public function testKeyFilter()
    {
        $this->assertEquals(
            'my_key',
            KeySanitizer::clean("My \nkey")
        );
    }
}
