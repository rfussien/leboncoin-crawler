<?php

namespace Lbc\Filter;

class CitySanitizer implements SanitizerInterface
{
    public function clean($value)
    {
        return (new DefaultSanitizer)
            ->clean(preg_replace('/[0-9]/', '', $value));
    }
}
