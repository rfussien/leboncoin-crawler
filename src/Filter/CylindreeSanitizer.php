<?php

namespace Lbc\Filter;

class CylindreeSanitizer implements SanitizerInterface
{
    public function clean($value)
    {
        return (int) preg_replace(
            '/ cm3/',
            '',
            (new DefaultSanitizer)->clean($value)
        );
    }
}
