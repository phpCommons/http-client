<?php
declare(strict_types=1);

namespace PhpCommons\HttpClient\Factory;

use PhpCommons\HttpClient\Request;

class RequestFactory
{
    /**
     * @param string $basePath
     * @return Request
     */
    public static function startsWith(string $basePath = ''): Request
    {
        return new Request($basePath);
    }
}