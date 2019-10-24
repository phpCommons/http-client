<?php

namespace Tests\Unit\HttpClient\Formatter;

use PhpCommons\HttpClient\Formatter\QueryParametersFormatter;
use PHPUnit\Framework\TestCase;

class QueryParametersFormatterTest extends TestCase
{
    /** @test */
    public function toHttpQueryParameterMethodShouldReturnHttpEncodedUrl()
    {
        $httpEncodedParameters = QueryParametersFormatter::toHttpQueryParameter([
            'username' => 'phpunit',
            'filterId' => md5('phpunit'),
        ]);

        $this->assertEquals(
            sprintf('?username=phpunit&filterId=%s', md5('phpunit')),
            $httpEncodedParameters
        );
    }

    /** @test */
    public function toHttpQueryParameterMethodShouldReturnAllDefinedParametersAsFlattenChain()
    {
        $httpEncodedParameters = QueryParametersFormatter::toHttpQueryParameter([
            'username' => ['phpunit', 'test', 'mock'],
            'filterId' => md5('phpunit'),
        ]);

        $expected = sprintf('?username=phpunit&username=test&username=mock&filterId=%s', md5('phpunit'));
        $this->assertEquals($expected, $httpEncodedParameters);
    }
}
