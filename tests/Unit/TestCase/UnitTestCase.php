<?php

namespace Tests\Unit\TestCase;

use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UnitTestCase extends TestCase
{
    use PHPMock;

    protected $namespace;

    protected function setNamespace(string $namespace = null): UnitTestCase
    {
        $this->namespace = $namespace ?: __NAMESPACE__;

        return $this;
    }

    protected function mockFunction(string $functionName): MockObject
    {
        return $this->getFunctionMock($this->namespace, $functionName);
    }
}