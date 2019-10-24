<?php

namespace Tests\Unit\HttpClient\Factory;

use PhpCommons\HttpClient\Factory\RequestFactory;
use Tests\Unit\TestCase\UnitTestCase;

class RequestFactoryTest extends UnitTestCase
{
    /** @test */
    public function createRequestWhichStartsWithPassedPath()
    {
        $request = RequestFactory::startsWith('/base/path');
        $this->assertEquals('/base/path', $request->path());
    }
}
