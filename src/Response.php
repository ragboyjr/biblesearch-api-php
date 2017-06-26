<?php

namespace BibleSearch\Api;

use ArrayAccess;
use ArrayIterator;
use BadMethodCallException;
use IteratorAggregate;
use Psr\Http;
use Krak\Arr;

class Response implements ArrayAccess, IteratorAggregate
{
    private $parsed_body;
    private $meta;
    private $http_response;

    public function __construct(Http\Message\ResponseInterface $http_response) {
        $this->http_response = $http_response;
        if ($http_response->getHeaderLine('Content-Type') == 'application/javascript') {
            $this->parsed_body = json_decode((string) $http_response->getBody(), true);
            $this->meta = Arr\get($this->parsed_body, 'response.meta');
        }
    }

    public function getStatus() {
        return $this->http_response->getStatusCode();
    }

    public function isOk() {
        return $this->getStatus() >= 200 && $this->getStatus() < 300;
    }

    public function isError() {
        return $this->getStatus() >= 400 && $this->getStatus() < 600;
    }

    public function getBody() {
        return $this->parsed_body ?: $this->http_response->getBody();
    }

    public function getMeta() {
        return $this->meta;
    }

    public function withNestedEntity(callable $nest) {
        $resp = clone $this;
        if ($resp->isOk() && $resp->parsed_body) {
            $resp->parsed_body = $nest($resp->parsed_body);
        }
        return $resp;
    }

    public function getHttpResponse() {
        return $this->http_response;
    }

    public function offsetSet($key, $value) {
        throw new BadMethodCallException('Response data is read only');
    }

    public function offsetGet($key) {
        return Arr\get($this->parsed_body, $key);
    }

    public function offsetExists($key) {
        return Arr\has($this->parsed_body, $key);
    }

    public function offsetUnset($key) {
        throw new BadMethodCallException('Response data is read only');
    }

    public function getIterator() {
        return new ArrayIterator($this->parsed_body);
    }
}
