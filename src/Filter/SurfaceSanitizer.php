<?php

namespace Lbc\Filter;

class SurfaceSanitizer implements SanitizerInterface
{
    public static function clean($value)
    {
        return (int) preg_replace('/ cm2/', '', DefaultSanitizer::clean($value));
    }
}
