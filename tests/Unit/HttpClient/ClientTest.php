<?php
declare(strict_types=1);

namespace Tests\Unit\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use PhpCommons\HttpClient\Client;
use PhpCommons\HttpClient\Factory\RequestFactory;
use PhpCommons\HttpClient\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Tests\Unit\TestCase\UnitTestCase;

class ClientTest extends UnitTestCase
{
    /** @test */
    public function whenClientSendRequestByGuzzleClientThenResponseShouldHaveReturnedBody(): void
    {
        $responseBody = [
            'message' => 'empty.data'
        ];
        $streamInterfaceMock = $this->createMock(StreamInterface::class);
        $streamInterfaceMock
            ->method('getContents')
            ->willReturn(json_encode($responseBody, JSON_THROW_ON_ERROR, 512));

        $responseMock = $this->createMock(GuzzleResponse::class);
        $responseMock
            ->method('getBody')
            ->willReturn($streamInterfaceMock);

        $guzzleClientMock = $this->createMock(GuzzleClient::class);
        $guzzleClientMock
            ->method('request')
            ->willReturn($responseMock);

        $client = new Client(
            'https://info.example.com/',
            'api/v3/',
            [],
            $guzzleClientMock
        );
        $request = RequestFactory::startsWith('/users');
        $request
            ->get()
            ->withParameters(['username' => 'phpunit']);
        $response = $client->send($request);

        $this->assertEquals($responseBody, $response->toArray());
    }

    /** @test */
    public function whenClientSendPostRequestByGuzzleClientThenRequestHasCorrectMethodPathAndBody(): void
    {
        $requestBody = ['username' => 'phpunit'];
        $requestArray = [
            'method' => null,
            'path' => null,
            'headers' => null
        ];

        $guzzleClientMock = $this->createMock(GuzzleClient::class);
        $guzzleClientMock
            ->method('request')
            ->willReturnCallback(function ($method, $path, $headers) use (&$requestArray) {
                $requestArray['method'] = $method;
                $requestArray['path'] = $path;
                $requestArray['headers'] = $headers;
                return $this->createMock(GuzzleResponse::class);
            });

        $client = new Client(
            'https://info.example.com/',
            'api/v3/',
            [],
            $guzzleClientMock
        );

        $request = RequestFactory::startsWith('/users')
            ->post('/new')
            ->withJsonBody($requestBody);
        $client->send($request);

        $this->assertEquals($requestArray['method'], Request::METHOD_POST);
        $this->assertEquals($requestArray['path'], '/api/v3/users/new');
        $this->assertEquals(
            $requestArray['headers']['body'],
            json_encode($requestBody, JSON_THROW_ON_ERROR, 512)
        );
    }

    /** @test */
    public function whenClientSendGetRequestWithParametersThenRequestShouldHaveCorrectParamters(): void
    {
        $requestParameters = ['username' => 'phpunit'];
        $requestArray = [
            'method' => null,
            'path' => null,
            'headers' => null
        ];

        $guzzleClientMock = $this->createMock(GuzzleClient::class);
        $guzzleClientMock
            ->method('request')
            ->willReturnCallback(function ($method, $path, $headers) use (&$requestArray) {
                $requestArray['method'] = $method;
                $requestArray['path'] = $path;
                $requestArray['headers'] = $headers;

                return $this->createMock(ResponseInterface::class);
            });

        $client = new Client(
            'https://info.example.com/',
            'api/v3/',
            [],
            $guzzleClientMock
        );

        $request = RequestFactory::startsWith('/users')
            ->get('/list')
            ->withParameters($requestParameters);
        $client->send($request);

        $this->assertEquals($requestArray['method'], Request::METHOD_GET);
        $this->assertEquals($requestArray['path'], '/api/v3/users/list');
        $this->assertEquals($requestArray['headers']['query'], $requestParameters);
    }

    /** @test */
    public function whenClientSendGetRequestAndGuzzleResponseFailsThenResponseWithExceptionShouldBeCreated(): void
    {
        $requestParameters = ['username' => 'phpunit'];
        $requestArray = [
            'method' => null,
            'path' => null,
            'headers' => null
        ];

        $guzzleClientMock = $this->createMock(GuzzleClient::class);
        $guzzleClientMock
            ->method('request')
            ->willReturnCallback(function ($method, $path, $headers) use (&$requestArray) {
                $requestArray['method'] = $method;
                $requestArray['path'] = $path;
                $requestArray['headers'] = $headers;

                $uri = $this->createMock(UriInterface::class);
                $requestMock = $this->createMock(RequestInterface::class);
                $requestMock
                    ->method('getUri')
                    ->willReturn($uri);

                $responseInterfaceMock = $this->createMock(ResponseInterface::class);
                $streamInterfaceMock = $this->createMock(StreamInterface::class);
                $streamInterfaceMock
                    ->method('getContents')
                    ->willReturn(json_encode(['message' => 'not.found'], JSON_THROW_ON_ERROR, 512));
                $responseInterfaceMock
                    ->method('getBody')
                    ->willReturn($streamInterfaceMock);

                throw RequestException::create($requestMock, $responseInterfaceMock);
            });

        $client = new Client(
            'https://info.example.com/',
            'api/v3/',
            [],
            $guzzleClientMock
        );

        $request = RequestFactory::startsWith('/users')
            ->get('/list')
            ->withParameters($requestParameters);
        $response = $client->send($request);
        $this->assertInstanceOf(GuzzleException::class, $response->getException());
    }
}
