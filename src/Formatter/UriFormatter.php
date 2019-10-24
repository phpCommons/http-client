<?php

namespace PhpCommons\HttpClient\Formatter;

class UriFormatter
{
    /**
     * @param string $baseUri
     * @param string $protocol
     * @return string
     */
    public static function format($baseUri, $protocol = 'http')
    {
        if (strpos($baseUri, $protocol) > -1) {
            $restPaths = explode(':', $baseUri);
            if(count($restPaths) > 1) {
                $protocol = $restPaths[0];
                $restPath = sprintf('%s', $restPaths[1]);
                $path = PathFormatter::format(sprintf('/%s', $restPath));
                return sprintf('%s:/%s', $protocol, $path);
            }

            return $baseUri;
        }

        $path = PathFormatter::format(sprintf('/%s', $baseUri));
        return sprintf('%s:/%s', $protocol, $path);
    }
}