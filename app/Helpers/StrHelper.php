<?php

namespace App\Helpers;

use App\Helpers\Helper;

class StrHelper extends Helper {
    public static function glob_match($pattern, $string): bool
    {
        $pattern = str_replace(
            ['\*', '\?'],
            ['.*', '.'],
            preg_quote($pattern, '#')
        );

        return (bool)preg_match('#^'.$pattern.'$#i', $string);
    }

}
