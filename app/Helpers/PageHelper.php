<?php

namespace App\Helpers;

use Illuminate\Support\Str;

final class PageHelper extends Helper
{
    public static function makeSeoTitle(string $str='', int $length=0): ?string {
        return config('app.head_title_prefix') . " | " . Str::limit($str,  $length);
    }
}
