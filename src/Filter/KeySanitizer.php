<?php

namespace Lbc\Filter;

use Lbc\Helper\Encoding;

class KeySanitizer implements SanitizerInterface
{
    public static function clean($value)
    {
        return preg_replace(
            '/\s/',
            '_',
            trim(strtolower(Encoding::toAscii($value)))
        );
    }
}
