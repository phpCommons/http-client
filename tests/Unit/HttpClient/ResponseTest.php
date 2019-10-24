<?php

namespace Tests\Unit\HttpClient;

use Exception;
use PhpCommons\HttpClient\Response;
use Tests\Unit\TestCase\UnitTestCase;

class ResponseTest extends UnitTestCase
{

    /** @test */
    public function thatCreateResponseFromResponseInterfaceReturnsNewResponseObject()
    {
        $responseInterfaceMock = $this->getMock('GuzzleHttp\Message\ResponseInterface');
        $response = Response::fromResponse($responseInterfaceMock);

        $this->assertInstanceOf('PhpCommons\HttpClient\Response', $response);
    }

    /** @test */
    public function thatCreateResponseFromExceptionReturnsNewResponseObject()
    {
        $exception = $this->getMock('Exception');
        $response = Response::fromException($exception);

        $this->assertInstanceOf('PhpCommons\HttpClient\Response', $response);
    }

    /** @test */
    public function whenSourceResponseHasBodyWithContentsThenGetBodyReturnsPassedBody()
    {
        $body = [
            'message' => 'this.is.body',
        ];
        $streamInterfaceMock = $this->getMock('GuzzleHttp\Stream\StreamInterface');
        $streamInterfaceMock
            ->method('getContents')
            ->willReturn(json_encode($body));

        $responseInterfaceMock = $this->getMock('GuzzleHttp\Message\ResponseInterface');
        $responseInterfaceMock
            ->method('getBody')
            ->willReturn($streamInterfaceMock);

        $response = Response::fromResponse($responseInterfaceMock);
        $this->assertEquals(json_encode($body), $response->getBody());
    }

    /** @test */
    public function whenResponseHasBodyThenGetBodyReturnsFirstTimePassedBody()
    {
        $body = [
            'message' => 'this.is.body',
        ];
        $streamInterfaceMock = $this->getMock('GuzzleHttp\Stream\StreamInterface');
        $streamInterfaceMock
            ->expects($this->once())
            ->method('getContents')
            ->willReturn(json_encode($body));

        $responseInterfaceMock = $this->getMock('GuzzleHttp\Message\ResponseInterface');
        $responseInterfaceMock
            ->method('getBody')
            ->willReturn($streamInterfaceMock);

        $response = Response::fromResponse($responseInterfaceMock);
        $response->getBody();
        $this->assertEquals(json_encode($body), $response->getBody());
    }

    /** @test */
    public function whenResponseBodyIsSetThenToArrayMethodReturnsAnArray()
    {
        $body = [
            'message' => 'this.is.body',
        ];
        $streamInterfaceMock = $this->getMock('GuzzleHttp\Stream\StreamInterface');
        $streamInterfaceMock
            ->expects($this->once())
            ->method('getContents')
            ->willReturn(json_encode($body));

        $responseInterfaceMock = $this->getMock('GuzzleHttp\Message\ResponseInterface');
        $responseInterfaceMock
            ->method('getBody')
            ->willReturn($streamInterfaceMock);

        $response = Response::fromResponse($responseInterfaceMock);
        $response->getBody();
        $this->assertEquals($body, $response->toArray());
    }

    /** @test */
    public function whenResponseHasBodyThanToObjectMethodReturnsObjectInstanceOfPassedClass()
    {
        $responseData = [
            'id' => 1,
            'name' => 'MyName',
        ];

        $streamMock = $this->getMock('GuzzleHttp\Stream\StreamInterface');
        $streamMock->method('getContents')
            ->willReturn(json_encode($responseData));

        $responseMock = $this->getMock('GuzzleHttp\Message\ResponseInterface');
        $responseMock->method('getBody')
            ->willReturn($streamMock);

        $response = Response::fromResponse($responseMock);

        $nameValueObjectMock = $response->toObject('Tests\Unit\Beans\NameValueObjectBean');

        $this->assertInstanceOf('Tests\Unit\Beans\NameValueObjectBean', $nameValueObjectMock);
    }

    /** @test */
    public function whenNoResponseThenCodeShouldBe500()
    {
        $responseMock = $this
            ->getMockBuilder('PhpCommons\HttpClient\Response', ['getBody'])
            ->disableOriginalConstructor()
            ->getMock();

        $responseMock
            ->method('getBody')
            ->willReturn(json_encode([]));

        $response = Response::fromException(new Exception());

        $this->assertEquals(500, $response->getCode());
    }
}
