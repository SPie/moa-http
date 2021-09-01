<?php

namespace Moa\Tests\Unit\Http\Factories;

use Moa\Http\Contracts\HeadersFactory;
use Moa\Http\Contracts\StreamFactory;
use Moa\Http\Factories\RequestFactory;
use Moa\Http\Request;
use Moa\Tests\Mocks\HttpMocks;
use Moa\Tests\TestCase;

final class RequestFactoryTest extends TestCase
{
    use HttpMocks;

    private function getRequestFactory( HeadersFactory $headersFactory = null, StreamFactory $streamFactory = null): RequestFactory
    {
        return new RequestFactory(
            $headersFactory ?: $this->createHeadersFactory(),
            $streamFactory ?: $this->createStreamFactory()
        );
    }

    private function createRandomParseContentType(): string
    {
        $contentTypes = ['application/x-www-form-urlencoded', 'multipart/form-data'];

        return $contentTypes[\mt_rand(0, 1)];
    }

    private function setUpCreateServerRequestTest(bool $withParseContentType = false, bool $withParseMethod = false): array
    {
        $method = $withParseMethod
            ? $this->createRandomMethod(['GET', 'HEAD', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE'])
            : $this->createRandomMethod(['POST', 'PUT', 'PATCH']);
        $uri = $this->createUri();
        $serverParams = [$this->getFaker()->word => $this->getFaker()->word];
        $cookies = $this->createCookies();
        $headers = $this->createHeadersBag();
        $this
            ->mockHeadersGetCookies($headers, $cookies)
            ->mockHeadersGetContentType($headers, $withParseContentType ? $this->createRandomParseContentType() : $this->getFaker()->word);
        $headersFactory = $this->createHeadersFactory();
        $this->mockHeadersFactoryCreate($headersFactory, $headers);
        $body = $this->createStream();
        $streamFactory = $this->createStreamFactory();
        $this->mockStreamFactoryCreateStream($streamFactory, $body);
        $_POST = [$this->getFaker()->word => $this->getFaker()->word];
        $requestFactory = $this->getRequestFactory($headersFactory, $streamFactory);

        return [$requestFactory, $method, $uri, $serverParams, $headers, $cookies, $body, $_POST];
    }

    public function testCreateServerRequest(): void
    {
        /** @var RequestFactory $requestFactory */
        [$requestFactory, $method, $uri, $serverParams, $headers, $cookies, $body] = $this->setUpCreateServerRequestTest();

        $this->assertEquals(
            new Request($method, $uri, $headers, $cookies, $body, $serverParams),
            $requestFactory->createServerRequest($method, $uri, $serverParams)
        );
    }

    public function testCreateServerRequestWithParseContentTypeWithoutParseMethod(): void
    {
        /** @var RequestFactory $requestFactory */
        [$requestFactory, $method, $uri, $serverParams, $headers, $cookies, $body] = $this->setUpCreateServerRequestTest(true);

        $this->assertEquals(
            new Request($method, $uri, $headers, $cookies, $body, $serverParams),
            $requestFactory->createServerRequest($method, $uri, $serverParams)
        );
    }

    public function testCreateServerRequestWithParseContentTypeWithParseMethod(): void
    {
        /** @var RequestFactory $requestFactory */
        [
            $requestFactory,
            $method,
            $uri,
            $serverParams,
            $headers,
            $cookies,
            $body,
            $parsedBody
        ] = $this->setUpCreateServerRequestTest(true, true);

        $this->assertEquals(
            new Request($method, $uri, $headers, $cookies, $body, $serverParams, [], $parsedBody),
            $requestFactory->createServerRequest($method, $uri, $serverParams)
        );
    }
}
