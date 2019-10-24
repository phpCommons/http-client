<?php

namespace Tests\Unit\HttpClient;

use PhpCommons\HttpClient\Factory\RequestFactory;
use PhpCommons\HttpClient\Request;
use Tests\Unit\TestCase\UnitTestCase;

class RequestTest extends UnitTestCase
{
    /** @test */
    public function whenRequestHasUsersResourceAndAddPrefixappThenShouldReturnappUsersPath()
    {
        $request = RequestFactory::startsWith('/users');
        $this->assertEquals('/app/users', $request->path('/app'));
    }

    /** @test */
    public function whenHeadMethodHasListParameterThenPathShouldBeUserList()
    {
        $request = RequestFactory::startsWith('/users')
            ->head('list');

        $this->assertEquals('/users/list', $request->path());
    }

    /** @test */
    public function whenGetMethodHasListParameterThenPathShouldBeUserList()
    {
        $request = RequestFactory::startsWith('/users')
            ->get('list');

        $this->assertEquals('/users/list', $request->path());
    }

    /** @test */
    public function whenPostMethodIsJsonThenReturnedPathShouldBeUsersJson()
    {
        $request = RequestFactory::startsWith('/users')
            ->post('json');

        $this->assertEquals('/users/json', $request->path());
    }

    /** @test */
    public function whenPutMethodIsJsonThenReturnedPathShouldBeUsersJson()
    {
        $request = RequestFactory::startsWith('/users')
            ->put('json');

        $this->assertEquals('/users/json', $request->path());
    }

    /** @test */
    public function whenPatchMethodIsObjectThenReturnedPathShouldBeUsersObject()
    {
        $request = RequestFactory::startsWith('/users')
            ->patch('object');

        $this->assertEquals('/users/object', $request->path());
    }

    /** @test */
    public function whenDeleteMethodIsObjectThenReturnedPathShouldBeUsersObject()
    {
        $request = RequestFactory::startsWith('/users')
            ->delete('object');

        $this->assertEquals('/users/object', $request->path());
    }

    /** @test */
    public function whenPurgeMethodIsJsonThenReturnedPathShouldBeUsersJson()
    {
        $request = RequestFactory::startsWith('/users')
            ->purge('json');

        $this->assertEquals('/users/json', $request->path());
    }

    /** @test */
    public function whenOptionsMethodIsJsonThenReturnedPathShouldBeUsersJson()
    {
        $request = RequestFactory::startsWith('/users')
            ->options('json');

        $this->assertEquals('/users/json', $request->path());
    }

    /** @test */
    public function whenTraceMethodIsObjectThenReturnedPathShouldBeUsersObject()
    {
        $request = RequestFactory::startsWith('/users')
            ->trace('object');

        $this->assertEquals('/users/object', $request->path());
    }

    /** @test */
    public function whenConnectMethodIsObjectThenReturnedPathShouldBeUsersObject()
    {
        $request = RequestFactory::startsWith('/users')
            ->connect('object');

        $this->assertEquals('/users/object', $request->path());
    }

    /** @test */
    public function whenGetRequestPassedWithParametersThenShouldBeReturnsByMethodParameters()
    {
        $parameters = [
            'username' => 'phpunit',
            'filterId' => md5('filter-1234'),
        ];
        $request = RequestFactory::startsWith('/users')
            ->get('/list')
            ->withParameters($parameters);

        $this->assertEquals($parameters, $request->parameters());
    }

    /** @test */
    public function whenGetRequestHasNoParametersThenReturnsEmptyParametersArray()
    {
        $request = RequestFactory::startsWith('/users')
            ->get('/list');

        $this->assertEquals([], $request->parameters());
    }

    /** @test */
    public function whenGetRequestHasParametersThenMethodHasParametersShouldReturnTrue()
    {
        $request = RequestFactory::startsWith('/users')
            ->get('object')
            ->withParameters([
                'username' => 'phpunit',
            ]);

        $this->assertTrue($request->hasParameters());
    }

    /** @test */
    public function whenGetRequestHasNoParametersThenMethodHasParametersShouldReturnFale()
    {
        $request = RequestFactory::startsWith('/users')
            ->get('object');

        $this->assertFalse($request->hasParameters());
    }

    /** @test */
    public function whenPostRequestWithBodyThenReturnsFromMethodBody()
    {
        $body = json_encode(
            [
                'username' => 'phpunit',
                'filterId' => md5('phpunit'),
            ]
        );
        $request = RequestFactory::startsWith('/users')
            ->post('/json')
            ->withBody($body);

        $this->assertEquals($body, $request->body());
    }

    /** @test */
    public function whenPostRequestWithJsonBodyThenReturnsFromMethodBody()
    {
        $body = [
            'username' => 'phpunit',
            'filterId' => md5('phpunit'),
        ];
        $request = RequestFactory::startsWith('/users')
            ->post('/json')
            ->withJsonBody($body);

        $this->assertEquals($body, (array)json_decode(
            $request->body(),
            true,
            512
        ));
    }

    /** @test */
    public function whenPutHasBodyThenMethodHasBodyReturnsTrue()
    {
        $body = [
            'username' => 'phpunit',
            'filterId' => md5('phpunit'),
        ];
        $request = RequestFactory::startsWith('/users')
            ->put('/json')
            ->withJsonBody($body);

        $this->assertTrue($request->hasBody());
    }

    /** @test */
    public function whenPostHasJsonBodyThenMethodHasBodyReturnsTrue()
    {
        $body = [
            'username' => 'phpunit',
            'filterId' => md5('phpunit'),
        ];
        $request = RequestFactory::startsWith('/users')
            ->post('/json')
            ->withJsonBody($body);

        $this->assertTrue($request->hasBody());
    }

    /** @test */
    public function whenPutHasNoBodyThenMethodHasBodyReturnsFalse()
    {
        $request = RequestFactory::startsWith('/users')
            ->put('/json');

        $this->assertFalse($request->hasBody());
    }

    /** @test */
    public function whenHeadThenMethodShouldBeSetToHead()
    {
        $request = RequestFactory::startsWith('/users')
            ->head('list');

        $this->assertEquals(Request::METHOD_HEAD, $request->method());
    }

    /** @test */
    public function whenGetThenMethodShouldBeSetToGet()
    {
        $request = RequestFactory::startsWith('/users')
            ->get('list');

        $this->assertEquals(Request::METHOD_GET, $request->method());
    }

    /** @test */
    public function whenPostThenMethodShouldBeSetToPost()
    {
        $request = RequestFactory::startsWith('/users')
            ->post('json');

        $this->assertEquals(Request::METHOD_POST, $request->method());
    }

    /** @test */
    public function whenPutThenMethodShouldBeSetToPut()
    {
        $request = RequestFactory::startsWith('/users')
            ->put('json');

        $this->assertEquals(Request::METHOD_PUT, $request->method());
    }

    /** @test */
    public function whenPatchThenMethodShouldBeSetToPatch()
    {
        $request = RequestFactory::startsWith('/users')
            ->patch('object');

        $this->assertEquals(Request::METHOD_PATCH, $request->method());
    }

    /** @test */
    public function whenDeleteThenMethodShouldBeSetToDelete()
    {
        $request = RequestFactory::startsWith('/users')
            ->delete('object');
        $this->assertEquals(Request::METHOD_DELETE, $request->method());
    }

    /** @test */
    public function whenPurgeThenMethodShouldBeSetToPurge()
    {
        $request = RequestFactory::startsWith('/users')
            ->purge('json');

        $this->assertEquals(Request::METHOD_PURGE, $request->method());
    }

    /** @test */
    public function whenOptionsThenMethodShouldBeSetToOptions()
    {
        $request = RequestFactory::startsWith('/users')
            ->options('json');

        $this->assertEquals(Request::METHOD_OPTIONS, $request->method());
    }

    /** @test */
    public function whenTraceThenMethodShouldBeSetToTrace()
    {
        $request = RequestFactory::startsWith('/users')
            ->trace('object');

        $this->assertEquals(Request::METHOD_TRACE, $request->method());
    }

    /** @test */
    public function whenConnectThenMethodShouldBeSetToConnect()
    {
        $request = RequestFactory::startsWith('/users')
            ->connect('object');

        $this->assertEquals(Request::METHOD_CONNECT, $request->method());
    }

    /** @test */
    public function whenHeadRequestWithHeaderThenReturnsPassedHeader()
    {
        $request = RequestFactory::startsWith('/users')
            ->head('object')
            ->withHeader('X-AUTH-TOKEN', md5('TOKEN'));

        $expected = [
            'X-AUTH-TOKEN' => md5('TOKEN'),
        ];
        $this->assertEquals($expected, $request->headers());
    }

    /** @test */
    public function whenHeadRequestWithHeadersThenExpectsShouldBeTheSameAsPassed()
    {
        $headers = [
            'X-AUTH-TOKEN' => md5('TOKEN'),
        ];
        $request = RequestFactory::startsWith('/users')
            ->head('object')
            ->withHeaders($headers);

        $this->assertEquals($headers, $request->headers());
    }

    /** @test */
    public function whenHeadRequestHasHeadersAndNewHeadersSetThenReturnsAllHeaders()
    {
        $headers = [
            'X-AUTH-TOKEN' => md5('TOKEN'),
            'X-APPLICATION-TOKEN' => md5('X_APP_TOKEN'),
        ];
        $request = RequestFactory::startsWith('/users')
            ->head('object')
            ->withHeader('X-AUTH-TOKEN', md5('TOKEN'))
            ->withHeaders([
                'X-APPLICATION-TOKEN' => md5('X_APP_TOKEN'),
            ]);

        $this->assertEquals($headers, $request->headers());
    }
}
