<?php
declare(strict_types=1);

namespace PhpCommons\HttpClient;

use Exception;
use JsonMapper;
use KHerGe\JSON\JSON;
use Psr\Http\Message\ResponseInterface;

class Response
{
    /** @var ResponseInterface */
    private $response;

    /** @var Exception */
    private $exception;

    /** @var string */
    private $body;

    public static function fromResponse(ResponseInterface $response): Response
    {
        $createdResponse = new self();
        $createdResponse->response = $response;

        return $createdResponse;
    }

    public static function fromException(Exception $exception): Response
    {
        $newResponse = new self();
        $newResponse->exception = $exception;

        return $newResponse;
    }

    public function getBody(): string
    {
        if($this->response !== null && $this->body === null) {
            $this->body = $this->response->getBody()->getContents();
        }

        return $this->body;
    }

    public function toArray(): array
    {
        $json = new JSON();
        return (array) $json->decode($this->getBody());
    }

    public function toObject(string $className): object
    {
        $json = new JSON();
        $mapper = new JsonMapper();
        $array = (array) $json->decode($this->getBody());
        $object = (object) $json->decode($this->getBody());
        $mapper->bIgnoreVisibility = true;
        return $mapper->map($object, new $className(...array_values($array)));
    }

    public function getException(): Exception
    {
        return $this->exception;
    }

    public function getCode(): int
    {
        if($this->response !== null) {
            return $this->response->getStatusCode();
        }

        return 500;
    }
}