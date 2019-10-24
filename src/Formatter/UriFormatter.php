<?php
declare(strict_types=1);

namespace PhpCommons\HttpClient\Formatter;

class UriFormatter
{
    /** @return string */
    public static function format(string $baseUri, string $protocol = 'http'): string
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