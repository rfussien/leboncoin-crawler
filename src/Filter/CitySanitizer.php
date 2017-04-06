<?php

namespace Lbc\Filter;

class CitySanitizer implements SanitizerInterface
{
    public static function clean($value)
    {
        return DefaultSanitizer::clean(preg_replace('/[0-9]/', '', $value));
    }
}
