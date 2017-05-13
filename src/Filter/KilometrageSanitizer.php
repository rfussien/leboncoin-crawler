<?php

namespace Lbc\Filter;

class KilometrageSanitizer implements SanitizerInterface
{
    public function clean($value)
    {
        return (int) preg_replace('/\D/', '', $value);
    }
}
