<?php

namespace Lbc\Filter;

class CpSanitizer implements SanitizerInterface
{
    public function clean($value)
    {
        return preg_replace('/\D/', '', (new DefaultSanitizer)->clean($value));
    }
}
