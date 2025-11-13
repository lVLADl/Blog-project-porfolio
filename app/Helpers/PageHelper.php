<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class PageHelper extends Helper
{
    public static string $image_placeholder = 'https://placehold.co/1600x800';
    public static string $picsum_placeholder = 'https://picsum.photos/1600/800'; // /?random=21
    public static function resolveImageUrl(string|null $image): string {
        if($image) {
            return
                !\App\Helpers\StrHelper::glob_match('*://picsum.photos/*', $image) ?
                    Storage::url($image) : $image;
        }

        return self::$image_placeholder;
    }
    public static function makeSeoTitle(string $str='', int $length=0): ?string {
        return config('app.head_title_prefix') . " | " . Str::limit($str,  $length);
    }
}
