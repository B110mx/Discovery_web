<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class SiteCache
{
    public static function ttl()
    {
        return now()->addHours(config('colegio.cache.hours', 12));
    }

    public static function key(string $name): string
    {
        return config("colegio.cache.keys.{$name}", $name);
    }

    public static function forget(string $name): void
    {
        Cache::forget(self::key($name));
    }

    public static function forgetMany(array $names): void
    {
        foreach ($names as $name) {
            self::forget($name);
        }
    }

    public static function forgetPattern(string $pattern): void
    {
        foreach (array_keys(config('colegio.cache.keys', [])) as $name) {
            if (str($name)->is($pattern)) {
                self::forget($name);
            }
        }
    }
}
