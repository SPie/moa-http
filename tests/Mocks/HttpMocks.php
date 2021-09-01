<?php

namespace Moa\Tests\Mocks;

use Moa\Http\Contracts\Headers;
use Moa\Http\Contracts\HeadersFactory;
use Moa\Http\Contracts\Stream;
use Moa\Http\Contracts\StreamFactory;
use Moa\Http\Contracts\Uri;
use Moa\Http\Cookies;
use Moa\Http\Header;
use Mockery as m;
use Mockery\MockInterface;

trait HttpMocks
{
    /**
     * @return Uri|MockInterface
     */
    private function createUri(): Uri
    {
        return m::spy(Uri::class);
    }

    private function mockUriGetPath(MockInterface $uri, string $path): self
    {
        $uri
            ->shouldReceive('getPath')
            ->andReturn($path);

        return $this;
    }

    private function mockUriGetQuery(MockInterface $uri, string $query): self
    {
        $uri
            ->shouldReceive('getQuery')
            ->andReturn($query);

        return $this;
    }

    private function mockUriGetHost(MockInterface $uri, string $host): self
    {
        $uri
            ->shouldReceive('getHost')
            ->andReturn($host);

        return $this;
    }

    private function createStream(): Stream
    {
        return m::spy(Stream::class);
    }

    /**
     * @return StreamFactory|MockInterface
     */
    private function createStreamFactory(): StreamFactory
    {
        return m::spy(StreamFactory::class);
    }

    private function mockStreamFactoryCreateStream(MockInterface $streamFactory, Stream $stream): self
    {
        $streamFactory
            ->shouldReceive('createStream')
            ->andReturn($stream);

        return $this;
    }

    /**
     * @return Headers|MockInterface
     */
    private function createHeadersBag(): Headers
    {
        return m::spy(Headers::class);
    }

    private function mockHeadersBagSetHeader(MockInterface $headersBag, string $name, $header): self
    {
        $headersBag
            ->shouldReceive('setHeader')
            ->with($name, $header)
            ->andReturn($headersBag)
            ->once();

        return $this;
    }

    private function mockHeadersBagAddHeader(MockInterface $headersBag, string $name, $header): self
    {
        $headersBag
            ->shouldReceive('addHeader')
            ->with($name, $header)
            ->andReturn($headersBag)
            ->once();

        return $this;
    }

    private function assertHeadersBagAddHeader(MockInterface $headersBag, string $name, $header): self
    {
        $headersBag
            ->shouldHaveReceived('addHeader')
            ->with($name, $header)
            ->once();

        return $this;
    }

    private function mockHeadersBagGetHeaders(MockInterface $headersBag, array $headers): self
    {
        $headersBag
            ->shouldReceive('getHeaders')
            ->andReturn($headers);

        return $this;
    }

    private function mockHeadersBagGetHeader(MockInterface $headersBag, array $header, string $name): self
    {
        $headersBag
            ->shouldReceive('getHeader')
            ->with($name)
            ->andReturn($header);

        return $this;
    }

    private function mockHeadersBagGetHeaderLine(MockInterface $headersBag, string $header, string $name): self
    {
        $headersBag
            ->shouldReceive('getHeaderLine')
            ->with($name)
            ->andReturn($header);

        return $this;
    }

    private function mockHeadersBagRemoveHeader(MockInterface $headersBag, string $name): self
    {
        $headersBag
            ->shouldReceive('removeHeader')
            ->with($name)
            ->andReturn($headersBag)
            ->once();

        return $this;
    }

    private function mockHeadersGetCookies(MockInterface $headers, Cookies $cookies): self
    {
        $headers
            ->shouldReceive('getCookies')
            ->andReturn($cookies);

        return $this;
    }

    private function mockHeadersGetContentType(MockInterface $headers, ?string $contentTypes): self
    {
        $headers
            ->shouldReceive('getContentType')
            ->andReturn($contentTypes);

        return $this;
    }

    /**
     * @return HeadersFactory|MockInterface
     */
    private function createHeadersFactory(): HeadersFactory
    {
        return m::spy(HeadersFactory::class);
    }

    private function mockHeadersFactoryCreate(MockInterface $headersFactory, Headers $headers): self
    {
        $headersFactory
            ->shouldReceive('create')
            ->andReturn($headers);

        return $this;
    }

    private function createRandomMethod(array $filterMethods = []): string
    {
        $methods = \array_filter(
            ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'],
            fn (string $method) => !\in_array($method, $filterMethods)
        );
        \shuffle($methods);

        return \array_shift($methods);
    }

    private function createCookies(array $cookieParams = []): Cookies
    {
        return new Cookies($cookieParams);
    }

    private function createHeader(string $name = null, string $normalizeName = null, array $value = []): Header
    {
        return new Header(
            $name ?: $this->getFaker()->word,
            $normalizeName ?: $this->getFaker()->word,
            $value
        );
    }
}
