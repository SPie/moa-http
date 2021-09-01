<?php

namespace Moa\Tests\Unit\Http;

use Moa\Http\Headers;
use Moa\Tests\Mocks\HttpMocks;
use Moa\Tests\Reflection;
use Moa\Tests\TestCase;

final class HeadersTest extends TestCase
{
    use HttpMocks;
    use Reflection;

    private function getHeaders(): Headers
    {
        return new Headers();
    }

    private function getHeadersFromHeaders(Headers $headers): array
    {
        return $this->getReflectionProperty($headers, 'headers');
    }

    private function setHeadersFromHeaders(Headers $headers, array $headersArray): Headers
    {
        $this->setReflectionProperty($headers, 'headers', $headersArray);

        return $headers;
    }

    public function testSetHeaderWithStringValue(): void
    {
        $headerName = \strtoupper($this->getFaker()->word);
        $headerValue = $this->getFaker()->word;
        $headers = $this->getHeaders();

        $this->assertEquals($headers, $headers->setHeader($headerName, $headerValue));
        $this->assertEquals(
            [\strtolower($headerName) => $this->createHeader($headerName, \strtolower($headerName), [$headerValue])],
            $this->getHeadersFromHeaders($headers)
        );
    }

    public function testSetHeaderWithArrayValue(): void
    {
        $headerName = \strtoupper($this->getFaker()->word);
        $headerValue = $this->getFaker()->word;
        $headers = $this->getHeaders();

        $this->assertEquals($headers, $headers->setHeader($headerName, [$headerValue]));
        $this->assertEquals(
            [\strtolower($headerName) => $this->createHeader($headerName, \strtolower($headerName), [$headerValue])],
            $this->getHeadersFromHeaders($headers)
        );
    }

    public function testAddHeaderWithStringValue(): void
    {
        $headerName = \strtoupper($this->getFaker()->word);
        $headerValue = $this->getFaker()->word;
        $headers = $this->getHeaders();

        $this->assertEquals($headers, $headers->addHeader($headerName, $headerValue));
        $this->assertEquals(
            [\strtolower($headerName) => $this->createHeader($headerName, \strtolower($headerName), [$headerValue])],
            $this->getHeadersFromHeaders($headers)
        );
    }

    public function testAddHeaderWithArrayValue(): void
    {
        $headerName = \strtoupper($this->getFaker()->word);
        $headerValue = $this->getFaker()->word;
        $headers = $this->getHeaders();

        $this->assertEquals($headers, $headers->addHeader($headerName, [$headerValue]));
        $this->assertEquals(
            [\strtolower($headerName) => $this->createHeader($headerName, \strtolower($headerName), [$headerValue])],
            $this->getHeadersFromHeaders($headers)
        );
    }

    public function testAddHeaderWithStringValueWithExistingName(): void
    {
        $headerName = \strtoupper($this->getFaker()->word);
        $headerValue = $this->getFaker()->word;
        $existingValue = $this->getFaker()->word;
        $headers = $this->setHeadersFromHeaders(
            $this->getHeaders(),
            [\strtolower($headerName) => $this->createHeader($headerName, \strtolower($headerName), [$existingValue])]
        );

        $this->assertEquals($headers, $headers->addHeader($headerName, $headerValue));
        $this->assertEquals(
            [\strtolower($headerName) => $this->createHeader($headerName, \strtolower($headerName), [$existingValue, $headerValue])],
            $this->getHeadersFromHeaders($headers)
        );
    }

    public function testAddHeaderWithArrayValueWithExistingName(): void
    {
        $headerName = \strtoupper($this->getFaker()->word);
        $headerValue = $this->getFaker()->word;
        $existingValue = $this->getFaker()->word;
        $headers = $this->setHeadersFromHeaders(
            $this->getHeaders(),
            [\strtolower($headerName) => $this->createHeader($headerName, \strtolower($headerName), [$existingValue])]
        );

        $this->assertEquals($headers, $headers->addHeader($headerName, [$headerValue]));
        $this->assertEquals(
            [\strtolower($headerName) => $this->createHeader($headerName, \strtolower($headerName), [$existingValue, $headerValue])],
            $this->getHeadersFromHeaders($headers)
        );
    }

    public function testGetHeaders(): void
    {
        $headerName = \strtoupper($this->getFaker()->word);
        $headerValues = [$this->getFaker()->word, $this->getFaker()->word];
        $headers = $this->setHeadersFromHeaders(
            $this->getHeaders(),
            [\strtolower($headerName) => $this->createHeader($headerName, \strtolower($headerName), $headerValues)]
        );

        $this->assertEquals([\strtolower($headerName) => $headerValues], $headers->getHeaders());
    }

    public function testGetHeadersWithoutHeaders(): void
    {
        $this->assertEquals([], $this->getHeaders()->getHeaders());
    }

    public function testGetHeader(): void
    {
        $headerName = $this->getFaker()->word;
        $headerValue = $this->getFaker()->word;
        $headers = $this->setHeadersFromHeaders(
            $this->getHeaders(),
            [\strtolower($headerName) => $this->createHeader($headerName, \strtolower($headerName), [$headerValue])]
        );

        $this->assertEquals([$headerValue], $headers->getHeader($headerName));
    }

    public function testGetHeaderWithoutFoundHeader(): void
    {
        $headers = $this->setHeadersFromHeaders($this->getHeaders(), []);

        $this->assertEquals([], $headers->getHeader($this->getFaker()->word));
    }

    public function testGetHeaderLine(): void
    {
        $headerName = $this->getFaker()->word;
        $headerValues = [$this->getFaker()->word, $this->getFaker()->word];
        $headers = $this->setHeadersFromHeaders(
            $this->getHeaders(),
            [\strtolower($headerName) => $this->createHeader($headerName, \strtolower($headerName), $headerValues)]
        );

        $this->assertEquals(\implode(',', $headerValues), $headers->getHeaderLine($headerName));
    }

    public function testGetHeaderLineWithoutFoundHeader(): void
    {
        $headers = $this->setHeadersFromHeaders($this->getHeaders(), []);

        $this->assertEquals('', $headers->getHeaderLine($this->getFaker()->word));
    }

    public function testRemoveHeader(): void
    {
        $headerName = $this->getFaker()->word;
        $headers = $this->setHeadersFromHeaders(
            $this->getHeaders(),
            [\strtolower($headerName) => $this->createHeader($headerName, \strtolower($headerName), [$this->getFaker()->word])]
        );

        $this->assertEquals($headers, $headers->removeHeader($headerName));
        $this->assertEquals([], $this->getHeadersFromHeaders($headers));
    }

    public function testRemoveHeaderWithoutHeaderFound(): void
    {
        $headers = $this->getHeaders();

        $this->assertEquals($headers, $headers->removeHeader($this->getFaker()->word));
    }

    public function testGetCookies(): void
    {
        $cookieName = $this->getFaker()->word;
        $cookieValue = $this->getFaker()->word;
        $otherCookieName = $this->getFaker()->word;
        $otherCookieValue = $this->getFaker()->word;
        $headers = $this->setHeadersFromHeaders(
            $this->getHeaders(),
            [
                'cookie' => $this->createHeader(
                    'Cookie',
                    'cookie',
                    [\sprintf('%s=%s; %s=%s',  $cookieName, $cookieValue, $otherCookieName, $otherCookieValue)]
                )
            ]
        );

        $this->assertEquals(
            $this->createCookies([$cookieName => $cookieValue, $otherCookieName => $otherCookieValue]),
            $headers->getCookies()
        );
    }

    public function testGetCookiesWithoutCookieHeader(): void
    {
        $this->assertEquals($this->createCookies([]), $this->setHeadersFromHeaders($this->getHeaders(), [])->getCookies());
    }

    public function testGetCookiesWithOneCookie(): void
    {
        $cookieName = $this->getFaker()->word;
        $cookieValue = $this->getFaker()->word;
        $headers = $this->setHeadersFromHeaders(
            $this->getHeaders(),
            [
                'cookie' => $this->createHeader(
                    'Cookie',
                    'cookie',
                    [\sprintf('%s=%s',  $cookieName, $cookieValue)]
                )
            ]
        );

        $this->assertEquals($this->createCookies([$cookieName => $cookieValue]), $headers->getCookies());
    }

    public function testGetCookiesWithInvalidCookieString(): void
    {
        $headers = $this->setHeadersFromHeaders(
            $this->getHeaders(),
            ['cookie' => $this->createHeader('Cookie', 'cookie', [$this->getFaker()->word])]
        );

        $this->assertEquals($this->createCookies([]), $headers->getCookies());
    }

    public function testGetContentType(): void
    {
        $contentType = $this->getFaker()->word;
        $headers = $this->setHeadersFromHeaders(
            $this->getHeaders(),
            [
                'content-type' => $this->createHeader(
                    'Content-Type',
                    'content-type',
                    [\sprintf('%s; %s', $contentType, $this->getFaker()->word)]
                )
            ]
        );

        $this->assertEquals($contentType, $headers->getContentType());
    }

    public function testGetContentTypeWithoutContentTypeHeader(): void
    {
        $this->assertEquals('', $this->setHeadersFromHeaders($this->getHeaders(), [])->getContentType());
    }

    public function testGetContentTypeWithMultipleContentTypes(): void
    {
        $contentType = $this->getFaker()->word;
        $otherContentType = $this->getFaker()->word;
        $headers = $this->setHeadersFromHeaders(
            $this->getHeaders(),
            [
                'content-type' => $this->createHeader(
                    'Content-Type',
                    'content-type',
                    [
                        \sprintf('%s; %s', $contentType, $this->getFaker()->word),
                        \sprintf('%s; %s', $otherContentType, $this->getFaker()->word)
                    ]
                )
            ]
        );

        $this->assertEquals($otherContentType, $headers->getContentType());
    }
}
