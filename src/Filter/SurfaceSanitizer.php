<?php

namespace Lbc\Filter;

class SurfaceSanitizer implements SanitizerInterface
{
    public function clean($value)
    {
        return (int) preg_replace(
            '/ cm2/',
            '',
            (new DefaultSanitizer)->clean($value));
    }
}
