<?php

namespace Lbc\Filter;

interface SanitizerInterface
{
    public static function clean($value);
}
