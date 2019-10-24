<?php

namespace PhpCommons\HttpClient\Factory;

use PhpCommons\HttpClient\Request;

class RequestFactory
{
    /**
     * @param string $basePath
     * @return Request
     */
    public static function startsWith($basePath = '')
    {
        return new Request($basePath);
    }
}