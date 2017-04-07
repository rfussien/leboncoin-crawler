<?php

namespace Lbc\Filter;

class PiecesSanitizer implements SanitizerInterface
{
    public static function clean($value)
    {
        return (int) DefaultSanitizer::clean($value);
    }
}
