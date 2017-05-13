<?php

namespace Lbc\Filter;

class PiecesSanitizer implements SanitizerInterface
{
    public function clean($value)
    {
        return (int) (new DefaultSanitizer)->clean($value);
    }
}
