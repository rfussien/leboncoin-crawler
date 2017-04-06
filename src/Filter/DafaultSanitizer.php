<?php

namespace Lbc\Filter;

class DafaultSanitizer implements SanitizerInterface
{
    public static function clean($value)
    {
        return trim(preg_replace("/[\n]+/", ' ', $value));
    }
}
