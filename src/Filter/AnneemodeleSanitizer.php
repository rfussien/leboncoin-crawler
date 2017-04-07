<?php

namespace Lbc\Filter;

class AnneemodeleSanitizer implements SanitizerInterface
{
    public static function clean($value)
    {
        return (int) preg_replace('/\D/', '', trim($value));
    }
}
