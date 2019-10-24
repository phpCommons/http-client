<?php

namespace PhpCommons\HttpClient\Formatter;

class QueryParametersFormatter
{
    /**
     * Convert to query array to http query parameter.
     * @param array $paramArray
     * @return string
     */
    public static function toHttpQueryParameter(array $paramArray)
    {
        $queryParam = '?';

        $first = true;
        foreach ($paramArray as $key => $value) {
            $v = null;

            if (is_array($value)) {
                $v = implode(sprintf('&%s=', $key), $value);
            } else {
                $v = $value;
            }

            $prefix = !$first ? '&': '';
            $queryParam .= sprintf('%s%s=%s', $prefix, $key,$v);
            $first = false;
        }

        return $queryParam;
    }
}