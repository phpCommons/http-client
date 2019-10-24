<?php
declare(strict_types=1);

namespace Tests\Unit\HttpClient\Formatter;

use PhpCommons\HttpClient\Formatter\UriFormatter;
use Tests\Unit\TestCase\UnitTestCase;

class UriFormatterTest extends UnitTestCase
{
    /** @test */
    public function whenHostWasDefinedWithoutHttpThenFormatterShouldPrefixUriWithHttpProtocol(): void
    {
        $this->assertEquals('http://localhost', UriFormatter::format('localhost'));
    }

    /** @test */
    public function whenBaseUriWithoutHttpProtocolAndHasSlashThenFormatterShouldCleanUpUriToCorrectForm(): void
    {
        $this->assertEquals('http://localhost', UriFormatter::format('/localhost'));
    }

    /** @test */
    public function whenBaseUriHasHttpsProtocolThenUriShouldBeTheSameAsBaseUri(): void
    {
        $this->assertEquals('https://localhost', UriFormatter::format('https://localhost'));
    }

    /** @test */
    public function whenBaseUriHasHttpProtocolThenUriShouldBeTheSameAsBaseUri(): void
    {
        $this->assertEquals('http://localhost', UriFormatter::format('http://localhost'));
    }

    /** @test */
    public function whenBaseUriHasIncorrectDoublesSlashesThenFormatterReturnsCorrectForm(): void
    {
        $this->assertEquals(
            'http://localhost/a/b/c',
            UriFormatter::format('http://localhost//a///b////c')
        );
    }

    /** @test */
    public function whenBaseUriHasIncorrectFormatThenFormatterReturnsCorrectUri(): void
    {
        $this->assertEquals(
            'http://localhost/a/b/c',
            UriFormatter::format('http:/localhost//a///b////c')
        );
    }

    /** @test */
    public function whenBaseUriProtocolHasMoreThenOneSlashesThenFormatterReturnsCorrectUri(): void
    {
        $this->assertEquals(
            'http://localhost/a/b/c',
            UriFormatter::format('http:////localhost//a///b////c')
        );
    }

    /** @test */
    public function whenBaseUriHasEmptyPathThenReturnsBaseUri(): void
    {
        $this->assertEquals(
            'http://',
            UriFormatter::format('http:')
        );
    }

    /** @test */
    public function whenBaseUriHasOnlyProtocolThenUriReturnsPassedProtocol(): void
    {
        $this->assertEquals('http', UriFormatter::format('http'));
    }

    /** @test */
    public function whenBaseUriHasOnlyHttpsProtocolThenUriReturnsHttps(): void
    {
        $this->assertEquals('https', UriFormatter::format('https'));
    }
}
