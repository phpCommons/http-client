<?php

namespace PhpCommons\HttpClient;

use Exception;
use GuzzleHttp\Message\ResponseInterface;
use Herrera\Json\Exception\Exception as HerraJsonException;
use Herrera\Json\Exception\JsonException;
use Herrera\Json\Json;
use JsonMapper;
use JsonMapper_Exception;

class Response
{
    /** @var ResponseInterface */
    private $response;

    /** @var Exception */
    private $exception;

    /** @var string */
    private $body;

    public static function fromResponse(ResponseInterface $response)
    {
        $createdResponse = new self();
        $createdResponse->response = $response;

        return $createdResponse;
    }

    public static function fromException(Exception $exception)
    {
        $newResponse = new self();
        $newResponse->exception = $exception;

        return $newResponse;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        if($this->response !== null && $this->body === null) {
            $this->body = $this->response->getBody()->getContents();
        }

        return $this->body;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $json = new Json();
        return (array) $json->decode($this->getBody());
    }

    /**
     * @param string $className
     * @return object
     * @throws HerraJsonException
     * @throws JsonException
     * @throws JsonMapper_Exception
     */
    public function toObject($className)
    {
        $json = new Json();
        $mapper = new JsonMapper();
        $array = (array) $json->decode($this->getBody());
        $object = (object) $json->decode($this->getBody());
        $mapper->bIgnoreVisibility = true;
        return $mapper->map($object, new $className());
    }

    /**
     * @return Exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        if($this->response !== null) {
            return $this->response->getStatusCode();
        }

        return 500;
    }
}