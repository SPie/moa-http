<?php

namespace Moa\Tests\Mocks;

use Moa\Http\Contracts\HeadersBag;
use Moa\Http\Contracts\Stream;
use Moa\Http\Contracts\Uri;
use Mockery as m;
use Mockery\MockInterface;

/**
 * Trait HttpMocks
 *
 * @package Moa\Tests\Mocks
 */
trait HttpMocks
{
    /**
     * @return Uri|MockInterface
     */
    private function createUri(): Uri
    {
        return m::spy(Uri::class);
    }

    /**
     * @param Uri|MockInterface $uri
     * @param string        $path
     *
     * @return $this
     */
    private function mockUriGetPath(MockInterface $uri, string $path): self
    {
        $uri
            ->shouldReceive('getPath')
            ->andReturn($path);

        return $this;
    }

    /**
     * @param Uri|MockInterface $uri
     * @param string            $query
     *
     * @return $this
     */
    private function mockUriGetQuery(MockInterface $uri, string $query): self
    {
        $uri
            ->shouldReceive('getQuery')
            ->andReturn($query);

        return $this;
    }

    /**
     * @return Stream
     */
    private function createStream(): Stream
    {
        return m::spy(Stream::class);
    }

    /**
     * @return HeadersBag|MockInterface
     */
    private function createHeadersBag(): HeadersBag
    {
        return m::spy(HeadersBag::class);
    }

    /**
     * @param HeadersBag|MockInterface $headersBag
     * @param string                   $name
     * @param string|string[]          $header
     *
     * @return $this
     */
    private function mockHeadersBagSetHeader(MockInterface $headersBag, string $name, $header): self
    {
        $headersBag
            ->shouldReceive('setHeader')
            ->with($name, $header)
            ->andReturn($headersBag)
            ->once();

        return $this;
    }

    /**
     * @param HeadersBag|MockInterface $headersBag
     * @param string                   $name
     * @param string|string[]          $header
     *
     * @return $this
     */
    private function mockHeadersBagAddHeader(MockInterface $headersBag, string $name, $header): self
    {
        $headersBag
            ->shouldReceive('addHeader')
            ->with($name, $header)
            ->andReturn($headersBag)
            ->once();

        return $this;
    }

    /**
     * @param MockInterface $headersBag
     * @param array         $headers
     *
     * @return $this
     */
    private function mockHeadersBagGetHeaders(MockInterface $headersBag, array $headers): self
    {
        $headersBag
            ->shouldReceive('getHeaders')
            ->andReturn($headers);

        return $this;
    }

    /**
     * @param HeadersBag|MockInterface $headersBag
     * @param array                    $header
     * @param string                   $name
     *
     * @return $this
     */
    private function mockHeadersBagGetHeader(MockInterface $headersBag, array $header, string $name): self
    {
        $headersBag
            ->shouldReceive('getHeader')
            ->with($name)
            ->andReturn($header);

        return $this;
    }

    /**
     * @param HeadersBag|MockInterface $headersBag
     * @param string                   $header
     * @param string                   $name
     *
     * @return $this
     */
    private function mockHeadersBagGetHeaderLine(MockInterface $headersBag, string $header, string $name): self
    {
        $headersBag
            ->shouldReceive('getHeaderLine')
            ->with($name)
            ->andReturn($header);

        return $this;
    }

    /**
     * @param HeadersBag|MockInterface $headersBag
     * @param string                   $name
     *
     * @return $this
     */
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
