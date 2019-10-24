<?php
declare(strict_types=1);

namespace PhpCommons\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use PhpCommons\HttpClient\Formatter\UriFormatter;

class Client
{
    /** @var GuzzleClient */
    private $guzzleClient;

    /** @var string */
    private $basePath;

    public function __construct(
        string $baseUri,
        string $basePath = '',
        array $headers = [],
        ?GuzzleClient $guzzleClient = null
    ) {
        $this->basePath = $basePath;
        $this->guzzleClient = $guzzleClient ?: new GuzzleClient(array_merge([
            'base_uri' => UriFormatter::format($baseUri),
            'headers' => $headers,
        ]));
    }

    /** @return array|mixed|object|null */
    public function getBaseUri()
    {
        return $this->guzzleClient->getConfig('base_uri');
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function getFullBaseUri(): string
    {
        return UriFormatter::format(sprintf('%s/%s', $this->getBaseUri(), $this->getBasePath()));
    }

    public function send(Request $request): Response
    {
        $headers = [
            'headers' => $request->headers(),
        ];

        if ($request->hasBody()) {
            $headers['body'] = $request->body();
        }

        if ($request->hasParameters()) {
            $headers['query'] = $request->parameters();
        }

        try {
            $response = $this->guzzleClient->request(
                $request->method(),
                $request->path($this->basePath),
                $headers
            );

            return Response::fromResponse($response);
        } catch (GuzzleException $exception) {
            return Response::fromException($exception);
        }
    }
}