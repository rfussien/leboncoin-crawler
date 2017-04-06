<?php

namespace Lbc\Filter;

class PriceSanitizer implements SanitizerInterface
{
    public static function clean($value)
    {
        return (int) preg_replace('/\D/', '', trim($value));
    }
}
