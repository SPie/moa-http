<?php

namespace Moa\Tests\Mocks;

use Moa\Http\Contracts\HeadersBag;
use Moa\Http\Contracts\Stream;
use Moa\Http\Contracts\Uri;
use Mockery as m;
use Mockery\MockInterface;

trait HttpMocks
{
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

    private function createHeadersBag(): HeadersBag
    {
        return m::spy(HeadersBag::class);
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
}
