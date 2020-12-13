<?php

namespace Moa\Tests\Unit\Http;

use Moa\Http\Contracts\Header;
use Moa\Http\Contracts\HeadersBag;
use Moa\Http\Contracts\Stream;
use Moa\Http\Contracts\Uri;
use Moa\Http\Request;
use Moa\Tests\Mocks\HttpMocks;
use Moa\Tests\Reflection;
use Moa\Tests\TestCase;

/**
 * Class RequestTest
 *
 * @package Moa\Tests\Http
 */
final class RequestTest extends TestCase
{
    use HttpMocks;
    use Reflection;

    //region Tests

    /**
     * @return void
     */
    public function testGetProtocolVersionWithDefault(): void
    {
        $this->assertEquals('1.1', $this->getRequest()->getProtocolVersion());
    }

    /**
     * @return void
     */
    public function testGetProtocolVersionWithManuallySetProtocol(): void
    {
        $protocolVersion = $this->getFaker()->word;
        $request = $this->getRequest();
        $this->setReflectionProperty($request, 'protocolVersion', $protocolVersion);

        $this->assertEquals($protocolVersion, $request->getProtocolVersion());
    }

    /**
     * @return void
     */
    public function testSetProtocolVersionWithServerParams(): void
    {
        $protocolVersion = $this->getFaker()->word;
        $serverParams = ['SERVER_PROTOCOL' => \sprintf('HTTP/%s', $protocolVersion)];

        $this->assertEquals(
            $protocolVersion,
            $this->getRequest(null, null, null, [], null, $serverParams)
                ->getProtocolVersion()
        );
    }

    /**
     * @return void
     */
    public function testWithProtocolVersion(): void
    {
        $protocolVersion = $this->getFaker()->word;
        $request = $this->getRequest();

        $newRequest = $request->withProtocolVersion($protocolVersion);

        $this->assertEquals($protocolVersion, $newRequest->getProtocolVersion());
        $this->assertFalse($request === $newRequest);
    }

    /**
     * @return void
     */
    public function testGetHeader(): void
    {
        $headers = [$this->getFaker()->word => [$this->getFaker()->word]];
        $headerBag = $this->createHeadersBag();
        $this->mockHeadersBagGetHeaders($headerBag, $headers);

        $this->assertEquals($headers, $this->getRequest(null, null, $headerBag)->getHeaders());
    }

    /**
     * @return void
     */
    public function testHasHeaderWithHeader(): void
    {
        $headerName = $this->getFaker()->word;
        $headersBag = $this->createHeadersBag();
        $this->mockHeadersBagGetHeader($headersBag, [$this->getFaker()->word], $headerName);

        $this->assertTrue($this->getRequest(null, null, $headersBag)->hasHeader($headerName));
    }

    /**
     * @return void
     */
    public function testHasHeaderWithoutHeader(): void
    {
        $this->assertFalse($this->getRequest()->hasHeader($this->getFaker()->word));
    }

    /**
     * @return void
     */
    public function testGetHeaderWithHeader(): void
    {
        $headerName = $this->getFaker()->word;
        $header = [$this->getFaker()->word];
        $headers = $this->createHeadersBag();
        $this->mockHeadersBagGetHeader($headers, $header, $headerName);

        $this->assertEquals(
            $header,
            $this->getRequest(null, null, $headers)->getHeader($headerName)
        );
    }

    /**
     * @return void
     */
    public function testGetHeaderWithoutHeader(): void
    {
        $headerName = $this->getFaker()->word;
        $headers = $this->createHeadersBag();
        $this->mockHeadersBagGetHeader($headers, [], $headerName);

        $this->assertEquals(
            [],
            $this->getRequest(null, null, $headers)->getHeader($headerName)
        );
    }

    /**
     * @return void
     */
    public function testGetHeaderLine(): void
    {
        $headerName = $this->getFaker()->word;
        $headerLine = $this->getFaker()->word;
        $headers = $this->createHeadersBag();
        $this->mockHeadersBagGetHeaderLine($headers, $headerLine, $headerName);

        $this->assertEquals(
            $headerLine,
            $this->getRequest(null, null, $headers)->getHeaderLine($headerName)
        );
    }

    /**
     * @return void
     */
    public function testGetHeaderLineWithoutHeader(): void
    {
        $headerName = $this->getFaker()->word;
        $headers = $this->createHeadersBag();
        $this->mockHeadersBagGetHeaderLine($headers, '', $headerName);

        $this->assertEquals(
            '',
            $this->getRequest(null, null, $headers)->getHeaderLine($headerName)
        );
    }

    /**
     * @return void
     */
    public function testWithHeader(): void
    {
        $header = $this->getFaker()->word;
        $headerName = $this->getFaker()->word;
        $headers = $this->createHeadersBag();
        $this->mockHeadersBagSetHeader($headers, $headerName, $header);
        $request = $this->getRequest(null, null, $headers);

        $newRequest = $request->withHeader($headerName, $header);

        $this->assertFalse($newRequest === $request);
    }

    /**
     * @return void
     */
    public function testWithAddedHeader(): void
    {
        $header = $this->getFaker()->word;
        $headerName = $this->getFaker()->word;
        $headers = $this->createHeadersBag();
        $this->mockHeadersBagAddHeader($headers, $headerName, $header);
        $request = $this->getRequest(null, null, $headers);

        $newRequest = $request->withAddedHeader($headerName, $header);

        $this->assertFalse($newRequest === $request);
    }

    /**
     * @return void
     */
    public function testWithputHeader(): void
    {
        $headerName = $this->getFaker()->word;
        $headers = $this->createHeadersBag();
        $this->mockHeadersBagRemoveHeader($headers, $headerName);
        $request = $this->getRequest(null, null, $headers);

        $newRequest = $request->withoutHeader($headerName);

        $this->assertFalse($newRequest === $request);
    }

    /**
     * @return void
     */
    public function testGetBody(): void
    {
        $body = $this->createStream();

        $this->assertEquals(
            $body,
            $this->getRequest(null, null, null, [], $body)->getBody()
        );
    }

    /**
     * @return void
     */
    public function testWithBody(): void
    {
        $body = $this->createStream();
        $request = $this->getRequest(null, null, null, [], $body);

        $newRequest = $request->withBody($body);

        $this->assertEquals($body, $newRequest->getBody());
        $this->assertFalse($request === $newRequest);
    }

    /**
     * @return void
     */
    public function testGetRequestTargetWithRequestTarget(): void
    {
        $requestTarget = \sprintf('/%s', $this->getFaker()->word);
        $request = $this->getRequest();
        $this->setReflectionProperty($request, 'requestTarget', $requestTarget);

        $this->assertEquals($requestTarget, $request->getRequestTarget());
    }

    /**
     * @return void
     */
    public function testGetRequestTargetWithoutRequestTarget(): void
    {
        $this->assertEquals('/', $this->getRequest()->getRequestTarget());
    }

    /**
     * @return void
     */
    public function testGetRequestTargetWithRequestTargetFromUri(): void
    {
        $path = \sprintf('/%s', $this->getFaker()->word);
        $uri = $this->createUri();
        $this->mockUriGetPath($uri, $path);
        $request = $this->getRequest(null, $uri);

        $this->assertEquals(\sprintf('/%s', \trim($path, '/')), $request->getRequestTarget());
    }

    /**
     * @return void
     */
    public function testGetRequestTargetFromUriWithQueryParameters(): void
    {
        $path = $this->getFaker()->word;
        $queryParameters = \sprintf(
            '%s=%s&%s=%s',
            $this->getFaker()->word,
            $this->getFaker()->word,
            $this->getFaker()->word,
            $this->getFaker()->word
        );
        $uri = $this->createUri();
        $this
            ->mockUriGetPath($uri, $path)
            ->mockUriGetQuery($uri, $queryParameters);
        $request = $this->getRequest(null, $uri);

        $this->assertEquals(
            \sprintf('/%s?%s', \trim($path, '/'), $queryParameters),
            $request->getRequestTarget()
        );
    }

    /**
     * @return void
     */
    public function testWithRequestTarget(): void
    {
        $requestTarget = \sprintf('/%s', $this->getFaker()->word);
        $request = $this->getRequest();

        $newRequest = $request->withRequestTarget($requestTarget);

        $this->assertEquals($requestTarget, $newRequest->getRequestTarget());
        $this->assertFalse($newRequest === $request);
    }

    //endregion

    /**
     * @param string|null     $method
     * @param Uri|null        $uri
     * @param HeadersBag|null $headers
     * @param array           $cookies
     * @param Stream|null     $body
     * @param array           $serverParams
     * @param array           $uploadedFiles
     *
     * @return Request
     */
    private function getRequest(
        string $method = null,
        Uri $uri = null,
        HeadersBag $headers = null,
        array $cookies = [],
        Stream $body = null,
        array $serverParams = [],
        array $uploadedFiles = []
    ): Request {
        return new Request(
            $method ?: $this->createRandomMethod(),
            $uri ?: $this->createUri(),
            $headers ?: $this->createHeadersBag(),
            $cookies,
            $body ?: $this->createStream(),
            $serverParams,
            $uploadedFiles
        );
    }

    /**
     * @return string
     */
    private function createRandomMethod(): string
    {
        $methods = ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'];
        \shuffle($methods);

        return \array_shift($methods);
    }
}
