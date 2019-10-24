<?php

namespace PhpCommons\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\TransferException;
use PhpCommons\HttpClient\Formatter\UriFormatter;

class Client
{
    /** @var GuzzleClient */
    private $guzzleClient;

    /** @var string */
    private $basePath;

    /**
     * @param string $baseUri
     * @param string $basePath
     * @param array $headers
     * @param GuzzleClient|null $guzzleClient
     */
    public function __construct(
        $baseUri,
        $basePath = '',
        array $headers = [],
        GuzzleClient $guzzleClient = null
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
        return $this->guzzleClient->getBaseUrl();
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @return string
     */
    public function getFullBaseUri()
    {
        return UriFormatter::format(sprintf('%s/%s', $this->getBaseUri(), $this->getBasePath()));
    }

    public function send(Request $request)
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
            $request = $this->guzzleClient->createRequest(
                $request->method(),
                $request->path($this->basePath),
                $headers
            );

            $response = $this->guzzleClient->send($request);

            return Response::fromResponse($response);
        } catch (TransferException $exception) {
            return Response::fromException($exception);
        }
    }
}