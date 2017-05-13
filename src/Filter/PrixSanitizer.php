<?php

namespace Lbc\Filter;

class PrixSanitizer implements SanitizerInterface
{
    public function clean($value)
    {
        return (int) preg_replace('/\D/', '', trim($value));
    }
}
