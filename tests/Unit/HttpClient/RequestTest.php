<?php
declare(strict_types=1);

namespace Tests\Unit\HttpClient;

use PHPUnit\Framework\Attributes\Test;
use PhpCommons\HttpClient\Factory\RequestFactory;
use PhpCommons\HttpClient\Request;
use Tests\Unit\TestCase\UnitTestCase;

class RequestTest extends UnitTestCase
{
    #[Test]
    public function whenRequestHasUsersResourceAndAddPrefixappThenShouldReturnappUsersPath(): void
    {
        $request = RequestFactory::startsWith('/users');
        $this->assertEquals('/app/users', $request->path('/app'));
    }

    #[Test]
    public function whenHeadMethodHasListParameterThenPathShouldBeUserList(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->head('list');

        $this->assertEquals('/users/list', $request->path());
    }

    #[Test]
    public function whenGetMethodHasListParameterThenPathShouldBeUserList(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->get('list');

        $this->assertEquals('/users/list', $request->path());
    }

    #[Test]
    public function whenPostMethodIsJsonThenReturnedPathShouldBeUsersJson(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->post('json');

        $this->assertEquals('/users/json', $request->path());
    }

    #[Test]
    public function whenPutMethodIsJsonThenReturnedPathShouldBeUsersJson(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->put('json');

        $this->assertEquals('/users/json', $request->path());
    }

    #[Test]
    public function whenPatchMethodIsObjectThenReturnedPathShouldBeUsersObject(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->patch('object');

        $this->assertEquals('/users/object', $request->path());
    }

    #[Test]
    public function whenDeleteMethodIsObjectThenReturnedPathShouldBeUsersObject(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->delete('object');

        $this->assertEquals('/users/object', $request->path());
    }

    #[Test]
    public function whenPurgeMethodIsJsonThenReturnedPathShouldBeUsersJson(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->purge('json');

        $this->assertEquals('/users/json', $request->path());
    }

    #[Test]
    public function whenOptionsMethodIsJsonThenReturnedPathShouldBeUsersJson(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->options('json');

        $this->assertEquals('/users/json', $request->path());
    }

    #[Test]
    public function whenTraceMethodIsObjectThenReturnedPathShouldBeUsersObject(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->trace('object');

        $this->assertEquals('/users/object', $request->path());
    }

    #[Test]
    public function whenConnectMethodIsObjectThenReturnedPathShouldBeUsersObject(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->connect('object');

        $this->assertEquals('/users/object', $request->path());
    }

    #[Test]
    public function whenGetRequestPassedWithParametersThenShouldBeReturnsByMethodParameters(): void
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

    #[Test]
    public function whenGetRequestHasNoParametersThenReturnsEmptyParametersArray(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->get('/list');

        $this->assertEquals([], $request->parameters());
    }

    #[Test]
    public function whenGetRequestHasParametersThenMethodHasParametersShouldReturnTrue(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->get('object')
            ->withParameters([
                'username' => 'phpunit',
            ]);

        $this->assertTrue($request->hasParameters());
    }

    #[Test]
    public function whenGetRequestHasNoParametersThenMethodHasParametersShouldReturnFale(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->get('object');

        $this->assertFalse($request->hasParameters());
    }

    #[Test]
    public function whenPostRequestWithBodyThenReturnsFromMethodBody(): void
    {
        $body = json_encode(
            [
                'username' => 'phpunit',
                'filterId' => md5('phpunit'),
            ],
            JSON_THROW_ON_ERROR,
            512
        );
        $request = RequestFactory::startsWith('/users')
            ->post('/json')
            ->withBody($body);

        $this->assertEquals($body, $request->body());
    }

    #[Test]
    public function whenPostRequestWithJsonBodyThenReturnsFromMethodBody(): void
    {
        $body = [
            'username' => 'phpunit',
            'filterId' => md5('phpunit'),
        ];
        $request = RequestFactory::startsWith('/users')
            ->post('/json')
            ->withJsonBody($body);

        $this->assertEquals($body, (array) json_decode($request->body(), true, 512, JSON_THROW_ON_ERROR));
    }

    #[Test]
    public function whenPutHasBodyThenMethodHasBodyReturnsTrue(): void
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

    #[Test]
    public function whenPostHasJsonBodyThenMethodHasBodyReturnsTrue(): void
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

    #[Test]
    public function whenPutHasNoBodyThenMethodHasBodyReturnsFalse(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->put('/json');

        $this->assertFalse($request->hasBody());
    }

    #[Test]
    public function whenHeadThenMethodShouldBeSetToHead(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->head('list');

        $this->assertEquals(Request::METHOD_HEAD, $request->method());
    }

    #[Test]
    public function whenGetThenMethodShouldBeSetToGet(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->get('list');

        $this->assertEquals(Request::METHOD_GET, $request->method());
    }

    #[Test]
    public function whenPostThenMethodShouldBeSetToPost(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->post('json');

        $this->assertEquals(Request::METHOD_POST, $request->method());
    }

    #[Test]
    public function whenPutThenMethodShouldBeSetToPut(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->put('json');

        $this->assertEquals(Request::METHOD_PUT, $request->method());
    }

    #[Test]
    public function whenPatchThenMethodShouldBeSetToPatch(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->patch('object');

        $this->assertEquals(Request::METHOD_PATCH, $request->method());
    }

    #[Test]
    public function whenDeleteThenMethodShouldBeSetToDelete(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->delete('object');
        $this->assertEquals(Request::METHOD_DELETE, $request->method());
    }

    #[Test]
    public function whenPurgeThenMethodShouldBeSetToPurge(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->purge('json');

        $this->assertEquals(Request::METHOD_PURGE, $request->method());
    }

    #[Test]
    public function whenOptionsThenMethodShouldBeSetToOptions(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->options('json');

        $this->assertEquals(Request::METHOD_OPTIONS, $request->method());
    }

    #[Test]
    public function whenTraceThenMethodShouldBeSetToTrace(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->trace('object');

        $this->assertEquals(Request::METHOD_TRACE, $request->method());
    }

    #[Test]
    public function whenConnectThenMethodShouldBeSetToConnect(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->connect('object');

        $this->assertEquals(Request::METHOD_CONNECT, $request->method());
    }

    #[Test]
    public function whenHeadRequestWithHeaderThenReturnsPassedHeader(): void
    {
        $request = RequestFactory::startsWith('/users')
            ->head('object')
            ->withHeader('X-AUTH-TOKEN', md5('TOKEN'));

        $expected = [
            'X-AUTH-TOKEN' => md5('TOKEN'),
        ];
        $this->assertEquals($expected, $request->headers());
    }

    #[Test]
    public function whenHeadRequestWithHeadersThenExpectsShouldBeTheSameAsPassed(): void
    {
        $headers = [
            'X-AUTH-TOKEN' => md5('TOKEN'),
        ];
        $request = RequestFactory::startsWith('/users')
            ->head('object')
            ->withHeaders($headers);

        $this->assertEquals($headers, $request->headers());
    }

    #[Test]
    public function whenHeadRequestHasHeadersAndNewHeadersSetThenReturnsAllHeaders(): void
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
