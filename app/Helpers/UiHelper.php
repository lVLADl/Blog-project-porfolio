<?php

namespace App\Helpers;

final class UiHelper extends Helper
{
    protected static $badgeColors = [
        'primary' => 'bg-primary',
        'secondary' => 'bg-secondary',
        'success' => 'bg-success',
        'danger' => 'bg-danger',
        'warning' => 'bg-warning',
        'info' => 'bg-info',
        'light' => 'bg-light text-dark',
        'dark' => 'bg-dark',
    ];
    public static array $excludedBadgeColors = ['dark', 'secondary'];
    /**
     * Массив Bootstrap badge классов
     */
    public static function badgeColors(): array
    {
        return self::$badgeColors;
    }

    public static function findBadgeColorKey(string $color): ?string
    {
        return array_search($color, self::badgeColors());
    }
    public static function randomBadgeColor(array $except=[]): string
    {
        $except = array_merge(self::$excludedBadgeColors, $except);
        $colors = self::badgeColors();
        $colors = array_filter($colors,
            function($key) use ($except) {
                return !in_array($key, $except);
        }, ARRAY_FILTER_USE_KEY);
        return $colors[array_rand($colors)];
    }

}
