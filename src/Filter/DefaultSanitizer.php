<?php

namespace Lbc\Filter;

class DefaultSanitizer implements SanitizerInterface
{
    public function clean($value)
    {
        return trim(preg_replace("/[\n]+/", ' ', $value));
    }
}
