<?php

namespace Lbc\Helper;

class Encoding
{
    /**
     * Replace accent and remove unknown chars
     *
     * @param string $string
     * @return string
     */
    public static function toAscii($string)
    {
        $alnumPattern = '/^[a-zA-Z0-9 ]+$/';

        $string = iconv(
            mb_detect_encoding($string),
            'ASCII//TRANSLIT',
            $string
        );

        $ret = array_map(function ($chr) use ($alnumPattern) {
            if (preg_match($alnumPattern, $chr)) {
                return $chr;
            }
            return '';
        }, str_split($string));

        return implode($ret);
    }
}
