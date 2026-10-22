<?php
declare(strict_types=1);

namespace Tests\Unit\HttpClient\Formatter;

use PHPUnit\Framework\Attributes\Test;
use PhpCommons\HttpClient\Formatter\UriFormatter;
use Tests\Unit\TestCase\UnitTestCase;

class UriFormatterTest extends UnitTestCase
{
    #[Test]
    public function whenHostWasDefinedWithoutHttpThenFormatterShouldPrefixUriWithHttpProtocol(): void
    {
        $this->assertEquals('http://localhost', UriFormatter::format('localhost'));
    }

    #[Test]
    public function whenBaseUriWithoutHttpProtocolAndHasSlashThenFormatterShouldCleanUpUriToCorrectForm(): void
    {
        $this->assertEquals('http://localhost', UriFormatter::format('/localhost'));
    }

    #[Test]
    public function whenBaseUriHasHttpsProtocolThenUriShouldBeTheSameAsBaseUri(): void
    {
        $this->assertEquals('https://localhost', UriFormatter::format('https://localhost'));
    }

    #[Test]
    public function whenBaseUriHasHttpProtocolThenUriShouldBeTheSameAsBaseUri(): void
    {
        $this->assertEquals('http://localhost', UriFormatter::format('http://localhost'));
    }

    #[Test]
    public function whenBaseUriHasIncorrectDoublesSlashesThenFormatterReturnsCorrectForm(): void
    {
        $this->assertEquals(
            'http://localhost/a/b/c',
            UriFormatter::format('http://localhost//a///b////c')
        );
    }

    #[Test]
    public function whenBaseUriHasIncorrectFormatThenFormatterReturnsCorrectUri(): void
    {
        $this->assertEquals(
            'http://localhost/a/b/c',
            UriFormatter::format('http:/localhost//a///b////c')
        );
    }

    #[Test]
    public function whenBaseUriProtocolHasMoreThenOneSlashesThenFormatterReturnsCorrectUri(): void
    {
        $this->assertEquals(
            'http://localhost/a/b/c',
            UriFormatter::format('http:////localhost//a///b////c')
        );
    }

    #[Test]
    public function whenBaseUriHasEmptyPathThenReturnsBaseUri(): void
    {
        $this->assertEquals(
            'http://',
            UriFormatter::format('http:')
        );
    }

    #[Test]
    public function whenBaseUriHasOnlyProtocolThenUriReturnsPassedProtocol(): void
    {
        $this->assertEquals('http', UriFormatter::format('http'));
    }

    #[Test]
    public function whenBaseUriHasOnlyHttpsProtocolThenUriReturnsHttps(): void
    {
        $this->assertEquals('https', UriFormatter::format('https'));
    }
}
