<?php

namespace Lbc\Filter;

interface SanitizerInterface
{
    public function clean($value);
}
