<?php

namespace Lbc\Filter;

class CpSanitizer implements SanitizerInterface
{
    public static function clean($value)
    {
        return preg_replace('/\D/', '', DefaultSanitizer::clean($value));
    }
}
