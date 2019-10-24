<?php

namespace Tests\Unit\HttpClient\Formatter;

use PhpCommons\HttpClient\Formatter\UriFormatter;
use Tests\Unit\TestCase\UnitTestCase;

class UriFormatterTest extends UnitTestCase
{
    /** @test */
    public function whenHostWasDefinedWithoutHttpThenFormatterShouldPrefixUriWithHttpProtocol()
    {
        $this->assertEquals('http://localhost', UriFormatter::format('localhost'));
    }

    /** @test */
    public function whenBaseUriWithoutHttpProtocolAndHasSlashThenFormatterShouldCleanUpUriToCorrectForm()
    {
        $this->assertEquals('http://localhost', UriFormatter::format('/localhost'));
    }

    /** @test */
    public function whenBaseUriHasHttpsProtocolThenUriShouldBeTheSameAsBaseUri()
    {
        $this->assertEquals('https://localhost', UriFormatter::format('https://localhost'));
    }

    /** @test */
    public function whenBaseUriHasHttpProtocolThenUriShouldBeTheSameAsBaseUri()
    {
        $this->assertEquals('http://localhost', UriFormatter::format('http://localhost'));
    }

    /** @test */
    public function whenBaseUriHasIncorrectDoublesSlashesThenFormatterReturnsCorrectForm()
    {
        $this->assertEquals(
            'http://localhost/a/b/c',
            UriFormatter::format('http://localhost//a///b////c')
        );
    }

    /** @test */
    public function whenBaseUriHasIncorrectFormatThenFormatterReturnsCorrectUri()
    {
        $this->assertEquals(
            'http://localhost/a/b/c',
            UriFormatter::format('http:/localhost//a///b////c')
        );
    }

    /** @test */
    public function whenBaseUriProtocolHasMoreThenOneSlashesThenFormatterReturnsCorrectUri()
    {
        $this->assertEquals(
            'http://localhost/a/b/c',
            UriFormatter::format('http:////localhost//a///b////c')
        );
    }

    /** @test */
    public function whenBaseUriHasEmptyPathThenReturnsBaseUri()
    {
        $this->assertEquals(
            'http://',
            UriFormatter::format('http:')
        );
    }

    /** @test */
    public function whenBaseUriHasOnlyProtocolThenUriReturnsPassedProtocol()
    {
        $this->assertEquals('http', UriFormatter::format('http'));
    }

    /** @test */
    public function whenBaseUriHasOnlyHttpsProtocolThenUriReturnsHttps()
    {
        $this->assertEquals('https', UriFormatter::format('https'));
    }
}
