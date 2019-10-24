<?php

namespace Tests\Unit\HttpClient;

use GuzzleHttp\Exception\RequestException;
use PhpCommons\HttpClient\Client;
use PhpCommons\HttpClient\Factory\RequestFactory;
use PhpCommons\HttpClient\Request;
use Tests\Unit\TestCase\UnitTestCase;

class ClientTest extends UnitTestCase
{
    /** @test */
    public function whenClientSendRequestByGuzzleClientThenResponseShouldHaveReturnedBody()
    {
        $responseBody = [
            'message' => 'empty.data',
        ];
        $streamInterfaceMock = $this->getMock('GuzzleHttp\Stream\StreamInterface');
        $streamInterfaceMock
            ->method('getContents')
            ->willReturn(json_encode($responseBody));

        $responseMock = $this->getMock('GuzzleHttp\Message\ResponseInterface');
        $responseMock
            ->method('getBody')
            ->willReturn($streamInterfaceMock);

        $requestMock = $this->getMockBuilder('GuzzleHttp\Message\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $guzzleClientMock = $this->getMock('GuzzleHttp\Client');
        $guzzleClientMock
            ->method('createRequest')
            ->willReturn($requestMock);

        $guzzleClientMock
            ->method('send')
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
    public function whenClientSendPostRequestByGuzzleClientThenRequestHasCorrectMethodPathAndBody()
    {
        $requestBody = ['username' => 'phpunit'];
        $requestArray = [
            'method' => null,
            'path' => null,
            'headers' => null,
        ];

        $guzzleClientMock = $this->getMock('GuzzleHttp\Client');
        $guzzleClientMock
            ->method('createRequest')
            ->willReturnCallback(function ($method, $path, $headers) use (&$requestArray) {
                $requestArray['method'] = $method;
                $requestArray['path'] = $path;
                $requestArray['headers'] = $headers;

                return $this->getMock('GuzzleHttp\Message\RequestInterface');
            });
        $guzzleClientMock
            ->method('send')
            ->willReturn(
                $this->getMockBuilder('GuzzleHttp\Message\FutureResponse')
                    ->disableOriginalConstructor()
                    ->getMock()
            );


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
            json_encode($requestBody)
        );
    }

    /** @test */
    public function whenClientSendGetRequestWithParametersThenRequestShouldHaveCorrectParamters()
    {
        $requestParameters = ['username' => 'phpunit'];
        $requestArray = [
            'method' => null,
            'path' => null,
            'headers' => null,
        ];

        $guzzleClientMock = $this->getMock('GuzzleHttp\Client');
        $guzzleClientMock
            ->method('createRequest')
            ->willReturnCallback(function ($method, $path, $headers) use (&$requestArray) {
                $requestArray['method'] = $method;
                $requestArray['path'] = $path;
                $requestArray['headers'] = $headers;

                return $this->getMock('GuzzleHttp\Message\RequestInterface');
            });

        $guzzleClientMock
            ->method('send')
            ->willReturn(
                $this->getMockBuilder('GuzzleHttp\Message\FutureResponse')
                    ->disableOriginalConstructor()
                    ->getMock()
            );

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
    public function whenClientSendGetRequestAndGuzzleResponseFailsThenResponseWithExceptionShouldBeCreated()
    {
        $requestParameters = ['username' => 'phpunit'];
        $requestArray = [
            'method' => null,
            'path' => null,
            'headers' => null,
        ];

        $guzzleClientMock = $this->getMock('GuzzleHttp\Client');
        $guzzleClientMock
            ->method('createRequest')
            ->willReturnCallback(function ($method, $path, $headers) use (&$requestArray) {
                $requestArray['method'] = $method;
                $requestArray['path'] = $path;
                $requestArray['headers'] = $headers;

                $requestMock = $this->getMock('GuzzleHttp\Message\RequestInterface');
                $requestMock
                    ->method('getUrl')
                    ->willReturn('/empty');

                $responseInterfaceMock = $this->getMock('GuzzleHttp\Message\ResponseInterface');
                $streamInterfaceMock = $this->getMock('GuzzleHttp\StreamStreamInterface');
                $streamInterfaceMock
                    ->method('getContents')
                    ->willReturn(json_encode(['message' => 'not.found']));
                $responseInterfaceMock
                    ->method('getBody')
                    ->willReturn($streamInterfaceMock);

                throw RequestException::create($requestMock, $responseInterfaceMock);
            });

        $guzzleClientMock
            ->method('send')
            ->willReturn(
                $this->getMockBuilder('GuzzleHttp\Message\FutureResponse')
                ->disableOriginalConstructor()
                ->getMock()
            );

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
        $this->assertInstanceOf('GuzzleHttp\Exception\TransferException', $response->getException());
    }
}
