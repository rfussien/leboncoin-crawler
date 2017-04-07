<?php

namespace Lbc\Filter;

class CylindreeSanitizer implements SanitizerInterface
{
    public static function clean($value)
    {
        return (int) preg_replace('/ cm3/', '', DefaultSanitizer::clean($value));
    }
}
