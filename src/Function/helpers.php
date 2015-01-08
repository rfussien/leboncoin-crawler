<?php

if (!function_exists('toAscii')) {
    /**
     * Replace accent and remove unknown chars
     * 
     * @param $string
     * @return string
     */
    function toAscii($string)
    {
        $alnumPattern = '/^[a-zA-Z0-9 ]+$/';

        if (preg_match($alnumPattern, $string)) {
            return $string;
        }

        $string = @iconv(mb_detect_encoding($string), 'ASCII//TRANSLIT', $string);

        $ret = array_map(
            function ($chr) use ($alnumPattern) {
                if (preg_match($alnumPattern, $chr)) {
                    return $chr;
                }
            },
            str_split($string)
        );

        return implode($ret);
    }
}
