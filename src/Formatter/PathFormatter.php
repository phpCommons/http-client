<?php

namespace PhpCommons\HttpClient\Formatter;

class PathFormatter
{
    /**
     * @param string $path
     * @return string
     */
    public static function format($path)
    {
        return preg_replace('~/+~', '/', $path);
    }
}