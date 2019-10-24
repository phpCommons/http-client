<?php
declare(strict_types=1);

namespace Tests\Unit\HttpClient\Factory;

use PhpCommons\HttpClient\Factory\RequestFactory;
use Tests\Unit\TestCase\UnitTestCase;

class RequestFactoryTest extends UnitTestCase
{
    /** @test */
    public function createRequestWhichStartsWithPassedPath(): void
    {
        $request = RequestFactory::startsWith('/base/path');
        $this->assertEquals('/base/path', $request->path());
    }
}
