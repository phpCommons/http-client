<?php

namespace Tests\Unit\TestCase;

use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class UnitTestCase extends TestCase
{
    use PHPMock;

    protected $namespace;

    /**
     * @param string|null $namespace
     * @return UnitTestCase
     */
    protected function setNamespace($namespace = null)
    {
        $this->namespace = $namespace ?: __NAMESPACE__;

        return $this;
    }

    /**
     * @param string $functionName
     * @return MockObject
     */
    protected function mockFunction($functionName)
    {
        return $this->getFunctionMock($this->namespace, $functionName);
    }
}