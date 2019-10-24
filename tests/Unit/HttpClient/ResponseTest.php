<?php
declare(strict_types=1);

namespace Tests\Unit\HttpClient;

use Exception;
use PhpCommons\HttpClient\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Tests\Unit\Beans\NameValueObjectBean;
use Tests\Unit\TestCase\UnitTestCase;

class ResponseTest extends UnitTestCase
{

    /** @test */
    public function thatCreateResponseFromResponseInterfaceReturnsNewResponseObject(): void
    {
        $responseInterfaceMock = $this->createMock(ResponseInterface::class);
        $response = Response::fromResponse($responseInterfaceMock);

        $this->assertInstanceOf(Response::class, $response);
    }

    /** @test */
    public function thatCreateResponseFromExceptionReturnsNewResponseObject(): void
    {
        $exception = $this->createMock(Exception::class);
        $response = Response::fromException($exception);

        $this->assertInstanceOf(Response::class, $response);
    }

    /** @test */
    public function whenSourceResponseHasBodyWithContentsThenGetBodyReturnsPassedBody(): void
    {
        $body = [
            'message' => 'this.is.body',
        ];
        $streamInterfaceMock = $this->createMock(StreamInterface::class);
        $streamInterfaceMock
            ->method('getContents')
            ->willReturn(json_encode($body, JSON_THROW_ON_ERROR, 512));

        $responseInterfaceMock = $this->createMock(ResponseInterface::class);
        $responseInterfaceMock
            ->method('getBody')
            ->willReturn($streamInterfaceMock);

        $response = Response::fromResponse($responseInterfaceMock);
        $this->assertEquals(json_encode($body, JSON_THROW_ON_ERROR, 512), $response->getBody());
    }

    /** @test */
    public function whenResponseHasBodyThenGetBodyReturnsFirstTimePassedBody(): void
    {
        $body = [
            'message' => 'this.is.body',
        ];
        $streamInterfaceMock = $this->createMock(StreamInterface::class);
        $streamInterfaceMock
            ->expects($this->once())
            ->method('getContents')
            ->willReturn(json_encode($body, JSON_THROW_ON_ERROR, 512));

        $responseInterfaceMock = $this->createMock(ResponseInterface::class);
        $responseInterfaceMock
            ->method('getBody')
            ->willReturn($streamInterfaceMock);

        $response = Response::fromResponse($responseInterfaceMock);
        $response->getBody();
        $this->assertEquals(json_encode($body, JSON_THROW_ON_ERROR, 512), $response->getBody());
    }

    /** @test */
    public function whenResponseBodyIsSetThenToArrayMethodReturnsAnArray(): void
    {
        $body = [
            'message' => 'this.is.body',
        ];
        $streamInterfaceMock = $this->createMock(StreamInterface::class);
        $streamInterfaceMock
            ->expects($this->once())
            ->method('getContents')
            ->willReturn(json_encode($body));

        $responseInterfaceMock = $this->createMock(ResponseInterface::class);
        $responseInterfaceMock
            ->method('getBody')
            ->willReturn($streamInterfaceMock);

        $response = Response::fromResponse($responseInterfaceMock);
        $response->getBody();
        $this->assertEquals($body, $response->toArray());
    }

    /** @test */
    public function whenResponseHasBodyThanToObjectMethodReturnsObjectInstanceOfPassedClass(): void
    {
        $responseData = [
            'id' => 1,
            'name' => 'MyName'
        ];

        $responseMock = $this->createPartialMock(Response::class, [
            'getBody',
        ]);

        $responseMock
            ->method('getBody')
            ->willReturn(json_encode($responseData, JSON_THROW_ON_ERROR, 512));

        $nameValueObjectMock = $responseMock->toObject(NameValueObjectBean::class);

        $this->assertInstanceOf(NameValueObjectBean::class, $nameValueObjectMock);
    }

    /** @test */
    public function whenNoResponseThenCodeShouldBe500(): void
    {
        $responseMock = $this->createPartialMock(Response::class, [
            'getBody',
        ]);

        $responseMock
            ->method('getBody')
            ->willReturn(json_encode([], JSON_THROW_ON_ERROR, 512));

        $response = Response::fromException(new Exception());

        $this->assertEquals(500, $response->getCode());
    }
}
