<?php

namespace Lbc\Filter;

class DefaultSanitizer implements SanitizerInterface
{
    public static function clean($value)
    {
        return trim(preg_replace("/[\n]+/", ' ', $value));
    }
}
