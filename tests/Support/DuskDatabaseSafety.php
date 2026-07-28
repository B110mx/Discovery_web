<?php

namespace Tests\Support;

use RuntimeException;

final class DuskDatabaseSafety
{
    public static function assertSafe(
        string $connection,
        string $database,
        string $basePath,
    ): void {
        $expected = self::normalizePath($basePath.'/database/dusk.sqlite');
        $actual = self::normalizePath(
            self::isAbsolutePath($database) ? $database : $basePath.'/'.$database,
        );

        if (
            $connection !== 'sqlite'
            || $database === ':memory:'
            || ! self::pathsAreEqual($actual, $expected)
        ) {
            throw new RuntimeException(sprintf(
                'Dusk fue bloqueado para proteger tus datos. Debe usar sqlite en [%s], pero recibió [%s] en [%s].',
                $expected,
                $connection,
                $database,
            ));
        }
    }

    private static function isAbsolutePath(string $path): bool
    {
        return str_starts_with($path, '/')
            || preg_match('/^[A-Za-z]:[\\\\\\/]/', $path) === 1;
    }

    private static function normalizePath(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        $prefix = '';

        if (preg_match('/^[A-Za-z]:/', $path, $matches) === 1) {
            $prefix = strtoupper($matches[0]);
            $path = substr($path, 2);
        } elseif (str_starts_with($path, '/')) {
            $prefix = '/';
        }

        $segments = [];

        foreach (explode('/', trim($path, '/')) as $segment) {
            if ($segment === '' || $segment === '.') {
                continue;
            }

            if ($segment === '..') {
                array_pop($segments);

                continue;
            }

            $segments[] = $segment;
        }

        return rtrim($prefix, '/').'/'.implode('/', $segments);
    }

    private static function pathsAreEqual(string $first, string $second): bool
    {
        return PHP_OS_FAMILY === 'Windows'
            ? strcasecmp($first, $second) === 0
            : $first === $second;
    }
}
