<?php
declare(strict_types=1);

namespace PhpCommons\HttpClient;

use PhpCommons\HttpClient\Formatter\PathFormatter;
use KHerGe\JSON\JSON;

class Request
{
    /** @see  https://github.com/symfony/symfony/blob/4.3/src/Symfony/Component/HttpFoundation/Request.php */
    public const METHOD_HEAD = 'HEAD';
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_PURGE = 'PURGE';
    public const METHOD_OPTIONS = 'OPTIONS';
    public const METHOD_TRACE = 'TRACE';
    public const METHOD_CONNECT = 'CONNECT';

    /** @var string */
    private $basePath;

    /** @var string */
    private $method;

    /** @var string */
    private $path;

    /** @var array|string[] */
    private $headers;

    /** @var array|string[] */
    private $parameters;

    /** @var string */
    private $body;

    public function __construct($basePath)
    {
        $this->basePath = $basePath;
        $this->method = self::METHOD_GET;
        $this->headers = [];
        $this->parameters = [];
        $this->body = '';
    }

    /** @return Request */
    public function head($path = ''): Request
    {
        return $this->httpMethod(self::METHOD_HEAD, $path);
    }

    /** @return Request */
    public function get($path = ''): Request
    {
        return $this->httpMethod(self::METHOD_GET, $path);
    }

    /** @return Request */
    public function post($path = ''): Request
    {
        return $this->httpMethod(self::METHOD_POST, $path);
    }

    /** @return Request */
    public function put($path = ''): Request
    {
        return $this->httpMethod(self::METHOD_PUT, $path);
    }

    /** @return Request */
    public function patch($path = ''): Request
    {
        return $this->httpMethod(self::METHOD_PATCH, $path);
    }

    /** @return Request */
    public function delete($path = ''): Request
    {
        return $this->httpMethod(self::METHOD_DELETE, $path);
    }

    /** @return Request */
    public function purge($path = ''): Request
    {
        return $this->httpMethod(self::METHOD_PURGE, $path);
    }

    /** @return Request */
    public function options($path = ''): Request
    {
        return $this->httpMethod(self::METHOD_OPTIONS, $path);
    }

    /** @return Request */
    public function trace($path = ''): Request
    {
        return $this->httpMethod(self::METHOD_TRACE, $path);
    }

    /** @return Request */
    public function connect($path = ''): Request
    {
        return $this->httpMethod(self::METHOD_CONNECT, $path);
    }

    private function httpMethod($method, $path = ''): Request
    {
        $request = clone $this;
        $request->method = $method;
        $request->path = $path;

        return $request;
    }

    /** @param array|string[] $parameters
     * @return Request
     */
    public function withParameters(array $parameters = []): Request
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function withBody(string $body): Request
    {
        $this->body = $body;

        return $this;
    }

    public function withJsonBody(array $body = []): Request
    {
        $json = new JSON();
        $this->body = $json->encode($body);

        return $this;
    }

    public function withHeader($key, $value): Request
    {
        $this->headers[$key] = $value;

        return $this;
    }

    public function withHeaders($headers = []): Request
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function path(string $prefixPath = ''): string
    {
        $restPath = !empty($this->path) ? PathFormatter::format(sprintf('/%s', $this->path)): '';
        $prefixPath = PathFormatter::format(sprintf('/%s', $prefixPath));
        $path = sprintf('%s%s%s', $prefixPath, $this->basePath, $restPath);
        return PathFormatter::format($path);
    }

    public function body(): string
    {
        return $this->body;
    }

    public function hasBody(): bool
    {
        return $this->body !== null && $this->body !== '';
    }

    public function parameters(): array
    {
        return $this->parameters;
    }

    public function hasParameters(): bool
    {
        return $this->parameters !== [];
    }

    public function headers(): array
    {
        return $this->headers;
    }
}