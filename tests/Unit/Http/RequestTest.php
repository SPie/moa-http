<?php

namespace Moa\Tests\Unit\Http;

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

    /**
     * @return void
     */
    public function testGetMethod(): void
    {
        $method = $this->createRandomMethod();

        $this->assertEquals($method, $this->getRequest($method)->getMethod());
    }

    /**
     * @return void
     */
    public function testWithMethod(): void
    {
        $method = $this->createRandomMethod();
        $request = $this->getRequest();

        $newRequest = $request->withMethod($method);

        $this->assertEquals($method, $newRequest->getMethod());
        $this->assertFalse($newRequest === $request);
    }

    /**
     * @return void
     */
    public function testGetUri(): void
    {
        $uri = $this->createUri();

        $this->assertEquals($uri, $this->getRequest(null, $uri)->getUri());
    }

    /**
     * @return void
     */
    public function testWithUri(): void
    {
        $host = $this->getFaker()->word;
        $uri = $this->createUri();
        $this->mockUriGetHost($uri, $host);
        $request = $this->getRequest();

        $newRequest = $request->withUri($uri);

        $this->assertEquals($uri, $newRequest->getUri());
        $this->assertFalse($newRequest === $request);
        $this->assertHeadersBagAddHeader($this->getReflectionProperty($newRequest, 'headers'), 'Host', $host);
    }

    /**
     * @return void
     */
    public function testWithUriWithPreexistingHost(): void
    {
        $uri = $this->createUri();
        $preExistingHost = $this->getFaker()->word;
        $headers = $this->createHeadersBag();
        $this->mockHeadersBagGetHeader($headers, [$preExistingHost], 'Host');
        $request = $this->getRequest();
        $this->setReflectionProperty($request, 'headers', $headers);

        $newRequest = $request->withUri($uri);

        $this->getReflectionProperty($newRequest, 'headers')->shouldNotHaveReceived('addHeader');
    }

    /**
     * @return void
     */
    public function testWithUriWithPreserveHostWithEmptyHostHeader(): void
    {
        $host = $this->getFaker()->word;
        $uri = $this->createUri();
        $this->mockUriGetHost($uri, $host);
        $request = $this->getRequest();

        $newRequest = $request->withUri($uri, true);

        $this->assertHeadersBagAddHeader($this->getReflectionProperty($newRequest, 'headers'), 'Host', $host);
    }

    /**
     * @return void
     */
    public function testWithUriWithPreserveHostWithNewHost(): void
    {
        $host = $this->getFaker()->word;
        $uri = $this->createUri();
        $this->mockUriGetHost($uri, $host);
        $request = $this->getRequest();

        $newRequest = $request->withUri($uri, true);

        $this->assertHeadersBagAddHeader($this->getReflectionProperty($newRequest, 'headers'), 'Host', $host);
    }

    /**
     * @return void
     */
    public function testWithUriWithPreserveHostWithOldHost(): void
    {
        $preexistingHost = $this->getFaker()->word;
        $headers = $this->createHeadersBag();
        $this->mockHeadersBagGetHeader($headers, [$preexistingHost], 'Host');
        $host = $this->getFaker()->word;
        $uri = $this->createUri();
        $this->mockUriGetHost($uri, $host);
        $request = $this->getRequest();
        $this->setReflectionProperty($request, 'headers', $headers);

        $newRequest = $request->withUri($uri, true);

        $this->getReflectionProperty($newRequest, 'headers')->shouldNotHaveReceived('addHeader');
    }

    /**
     * @return void
     */
    public function testGetServerParams(): void
    {
        $serverParams = [$this->getFaker()->word => $this->getFaker()->word];

        $this->assertEquals(
            $serverParams,
            $this->getRequest(null, null, null, [], null, $serverParams)->getServerParams()
        );
    }

    /**
     * @return void
     */
    public function testGetCookieParams(): void
    {
        $cookieParams = [$this->getFaker()->word => $this->getFaker()->word];

        $this->assertEquals(
            $cookieParams,
            $this->getRequest(null, null, null, $cookieParams)->getCookieParams()
        );
    }

    /**
     * @return void
     */
    public function testWithCookieParams(): void
    {
        $cookieParams = [$this->getFaker()->word => $this->getFaker()->word];
        $request = $this->getRequest(null, null, null, $cookieParams);

        $newRequest = $request->withCookieParams($cookieParams);

        $this->assertEquals($cookieParams, $newRequest->getCookieParams());
        $this->assertFalse($newRequest === $request);
    }

    /**
     * @return void
     */
    public function testGetQueryParams(): void
    {
        $name = $this->getFaker()->word;
        $value = $this->getFaker()->word;
        $uri = $this->createUri();
        $this->mockUriGetQuery($uri, \sprintf('%s=%s', $name, $value));
        $request = $this->getRequest(null, $uri);

        $this->assertEquals([$name => $value], $request->getQueryParams());
    }

    /**
     * @return void
     */
    public function testGetQueryParamsWithoutQuery(): void
    {
        $request = $this->getRequest();

        $this->assertEquals([], $request->getQueryParams());
    }

    /**
     * @return void
     */
    public function testGetQueryParamsWithEncodedParameter(): void
    {
        $uri = $this->createUri();
        $this->mockUriGetQuery($uri, \sprintf('%s=%s', '%24', '%25'));
        $request = $this->getRequest(null, $uri);

        $this->assertEquals(['$' => '%'], $request->getQueryParams());
    }

    /**
     * @return void
     */
    public function testWithQueryParams(): void
    {
        $queryParams = [$this->getFaker()->word => $this->getFaker()->word];
        $request = $this->getRequest();

        $newRequest = $request->withQueryParams($queryParams);

        $this->assertEquals($queryParams, $newRequest->getQueryParams());
        $this->assertFalse($newRequest === $request);
    }

    /**
     * @return void
     */
    public function testGetUploadedFiles(): void
    {
        $uploadedFiles = [$this->getFaker()->word => $this->getFaker()->word];
        $request = $this->getRequest(null, null, null, [], null, [], $uploadedFiles);

        $this->assertEquals($uploadedFiles, $request->getUploadedFiles());
    }

    /**
     * @return void
     */
    public function testWithUploadedFiles(): void
    {
        $uploadedFiles = [$this->getFaker()->word => $this->getFaker()->word];
        $request = $this->getRequest();

        $newRequest = $request->withUploadedFiles($uploadedFiles);

        $this->assertEquals($uploadedFiles, $newRequest->getUploadedFiles());
        $this->assertFalse($newRequest === $request);
    }

    /**
     * @return void
     */
    public function testGetParsedBody(): void
    {
        $parsedBody = [$this->getFaker()->word => $this->getFaker()->word];
        $request = $this->getRequest(null, null, null, [], null, [], [], $parsedBody);

        $this->assertEquals($parsedBody, $request->getParsedBody());
    }

    /**
     * @return void
     */
    public function testGetParsedBodyWithoutParsedBody(): void
    {
        $this->assertNull($this->getRequest()->getParsedBody());
    }

    /**
     * @return void
     */
    public function testWithParsedBody(): void
    {
        $parsedBody = [$this->getFaker()->word => $this->getFaker()->word];
        $request = $this->getRequest();

        $newRequest = $request->withParsedBody($parsedBody);

        $this->assertEquals($parsedBody, $newRequest->getParsedBody());
        $this->assertFalse($newRequest === $request);
    }

    /**
     * @return void
     */
    public function testWithParsedBodyWithoutParsedBody(): void
    {
        $request = $this->getRequest(null, null, null, [], null, [], [], [$this->getFaker()->word => $this->getFaker()->word]);

        $newRequest = $request->withParsedBody(null);

        $this->assertNull($newRequest->getParsedBody());
    }

    /**
     * @return void
     */
    public function testGetAttributes(): void
    {
        $attributes = [$this->getFaker()->word => $this->getFaker()->word];
        $request = $this->getRequest();
        $this->setReflectionProperty($request, 'attributes', $attributes);

        $this->assertEquals($attributes, $request->getAttributes());
    }

    /**
     * @return void
     */
    public function testGetAttribute(): void
    {
        $attribute = $this->getFaker()->word;
        $attributeName = $this->getFaker()->word;
        $request = $this->getRequest();
        $this->setReflectionProperty($request, 'attributes', [$attributeName => $attribute]);

        $this->assertEquals($attribute, $request->getAttribute($attributeName));
    }

    /**
     * @return void
     */
    public function testGetAttributeWithoutAttributeFound(): void
    {
        $default = $this->getFaker()->word;
        $request = $this->getRequest();

        $this->assertEquals($default, $request->getAttribute($this->getFaker()->word, $default));
    }

    /**
     * @return void
     */
    public function testWithAttribute(): void
    {
        $attribute = $this->getFaker()->word;
        $attributeName = $this->getFaker()->word;
        $request = $this->getRequest();

        $newRequest = $request->withAttribute($attributeName, $attribute);

        $this->assertEquals([$attributeName => $attribute], $newRequest->getAttributes());
        $this->assertFalse($newRequest === $request);
    }

    /**
     * @return void
     */
    public function testWithoutAttribute(): void
    {
        $attributeName = $this->getFaker()->word;
        $request = $this->getRequest();
        $this->setReflectionProperty($request, 'attributes', [$attributeName => $this->getFaker()]);

        $newRequest = $request->withoutAttribute($attributeName);

        $this->assertFalse(isset($newRequest->getAttributes()[$attributeName]));
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
     * @param array|null      $parsedBody
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
        array $uploadedFiles = [],
        array $parsedBody = null
    ): Request {
        return new Request(
            $method ?: $this->createRandomMethod(),
            $uri ?: $this->createUri(),
            $headers ?: $this->createHeadersBag(),
            $cookies,
            $body ?: $this->createStream(),
            $serverParams,
            $uploadedFiles,
            $parsedBody
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
