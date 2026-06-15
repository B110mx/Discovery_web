<?php

namespace App\Support;

class SchoolGradeFormatter
{
    public static function format(string $text): string
    {
        $text = str_replace('º', '°', $text);
        $text = preg_replace(
            '/\b(\d{1,2})(?!°)(?=\s+(?:a|al|y)\s+\d{1,2}°?\s+grados?\b)/u',
            '$1°',
            $text,
        ) ?? $text;

        return preg_replace('/\b(\d{1,2})(?!°)(?=\s+grados?\b)/u', '$1°', $text) ?? $text;
    }
}
