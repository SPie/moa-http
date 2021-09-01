<?php

namespace Moa\Http\Factories;

use Moa\Http\Contracts\Headers;
use Moa\Http\Contracts\HeadersFactory;
use Moa\Http\Contracts\RequestFactory as RequestFactoryContract;
use Moa\Http\Contracts\StreamFactory;
use Moa\Http\Request;
use Psr\Http\Message\ServerRequestInterface;

final class RequestFactory implements RequestFactoryContract
{
    public function __construct(private HeadersFactory $headersFactory, private StreamFactory $streamFactory)
    {
    }

    /**
     * @inheritDoc
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        $headers = $this->headersFactory->create();

        return new Request(
            $method,
            $uri,
            $headers,
            $headers->getCookies(),
            $this->streamFactory->createStream(),
            $serverParams,
            [],
            $this->getParsedBody($method, $headers)
        );
    }

    private function getParsedBody(string $method, Headers $headers): ?array
    {
        return $this->hasParsedBodyContentType($headers) && $this->hasParsedBodyMethod($method)
            ? $_POST
            : null;
    }

    private function hasParsedBodyContentType(Headers $headers): bool
    {
        return \in_array($headers->getContentType(), ['application/x-www-form-urlencoded', 'multipart/form-data']);
    }

    private function hasParsedBodyMethod(string $method): bool
    {
        return \in_array($method, ['POST', 'PUT', 'PATCH']);
    }
}
