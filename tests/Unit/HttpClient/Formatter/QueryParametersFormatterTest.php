<?php
declare(strict_types=1);

namespace Tests\Unit\HttpClient\Formatter;

use PHPUnit\Framework\Attributes\Test;
use PhpCommons\HttpClient\Formatter\QueryParametersFormatter;
use PHPUnit\Framework\TestCase;

class QueryParametersFormatterTest extends TestCase
{
    #[Test]
    public function toHttpQueryParameterMethodShouldReturnHttpEncodedUrl(): void
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

    #[Test]
    public function toHttpQueryParameterMethodShouldReturnAllDefinedParametersAsFlattenChain(): void
    {
        $httpEncodedParameters = QueryParametersFormatter::toHttpQueryParameter([
            'username' => ['phpunit', 'test', 'mock'],
            'filterId' => md5('phpunit'),
        ]);

        $expected = sprintf('?username=phpunit&username=test&username=mock&filterId=%s', md5('phpunit'));
        $this->assertEquals($expected, $httpEncodedParameters);
    }
}
