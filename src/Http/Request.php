<?php

namespace Moa\Http;

use Moa\Http\Contracts\HeadersBag;
use Moa\Http\Contracts\Request as RequestContract;
use Moa\Http\Contracts\Uri;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class Request
 *
 * @package Moa\Http
 */
final class Request implements RequestContract
{
    /**
     * @var string
     */
    private string $method;

    /**
     * @var UriInterface
     */
    private UriInterface $uri;

    /**
     * @var HeadersBag
     */
    private HeadersBag $headers;

    /**
     * @var array
     */
    private array $cookies;

    /**
     * @var StreamInterface
     */
    private StreamInterface $body;

    /**
     * @var array
     */
    private array $serverParams;

    /**
     * @var array
     */
    private array $uploadedFiles;

    /**
     * @var string
     */
    private string $protocolVersion;

    /**
     * @var string|null
     */
    private ?string $requestTarget;

    /**
     * @var array
     */
    private array $queryParams;

    /**
     * @var array
     */
    private array $attributes;

    /**
     * @var array|null
     */
    private ?array $parsedBody;

    /**
     * Request constructor.
     *
     * @param string          $method
     * @param Uri             $uri
     * @param HeadersBag      $headers
     * @param array           $cookies
     * @param StreamInterface $body
     * @param array           $serverParams
     * @param array           $uploadedFiles
     * @param array|null      $parsedBody
     */
    public function __construct(
        string $method,
        Uri $uri,
        HeadersBag $headers,
        array $cookies,
        StreamInterface $body,
        array $serverParams = [],
        array $uploadedFiles = [],
        array $parsedBody = null
    ) {
        $this->method        = $method;
        $this->uri           = $uri;
        $this->headers       = $headers;
        $this->cookies       = $cookies;
        $this->body          = $body;
        $this->serverParams  = $serverParams;
        $this->uploadedFiles = $uploadedFiles;
        $this->parsedBody = $parsedBody;

        $this->protocolVersion = !empty($this->serverParams['SERVER_PROTOCOL'])
            ? $this->protocolVersion = \str_replace('HTTP/', '', $this->serverParams['SERVER_PROTOCOL'])
            : '1.1';

        $this->requestTarget = null;

        $this->queryParams = [];
        \parse_str($uri->getQuery(), $this->queryParams);

        $this->attributes = [];
    }

    /**
     * @return string|void
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * @param string $version
     *
     * @return Request
     */
    public function withProtocolVersion($version)
    {
        $clone = clone $this;
        $clone->protocolVersion = $version;

        return $clone;
    }

    /**
     * @return \string[][]
     */
    public function getHeaders()
    {
        return $this->headers->getHeaders();
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasHeader($name)
    {
        return !empty($this->headers->getHeader($name));
    }

    /**
     * @param string $name
     *
     * @return string[]
     */
    public function getHeader($name)
    {
        return $this->headers->getHeader($name);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getHeaderLine($name)
    {
        return $this->headers->getHeaderLine($name);
    }

    /**
     * @param string          $name
     * @param string|string[] $value
     *
     * @return Request
     */
    public function withHeader($name, $value)
    {
        $clone = clone $this;
        $clone->headers->setHeader($name, $value);

        return $clone;
    }

    /**
     * @param string          $name
     * @param string|string[] $value
     *
     * @return Request|void
     */
    public function withAddedHeader($name, $value)
    {
        $clone = clone $this;
        $clone->headers->addHeader($name, $value);

        return $clone;
    }

    /**
     * @param string $name
     *
     * @return Request|void
     */
    public function withoutHeader($name)
    {
        $clone = clone $this;
        $clone->headers->removeHeader($name);

        return $clone;
    }

    /**
     * @return StreamInterface|void
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param StreamInterface $body
     *
     * @return Request|void
     */
    public function withBody(StreamInterface $body)
    {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }

    /**
     * @return string
     */
    public function getRequestTarget()
    {
        if ($this->requestTarget) {
            return $this->requestTarget;
        }

        if (empty($this->uri->getPath())) {
            return '/';
        }

        $path = \sprintf('/%s', \trim($this->uri->getPath(), '/'));
        if (!empty($this->uri->getQuery())) {
            $path = \sprintf('%s?%s', $path, $this->uri->getQuery());
        }

        return $path;
    }

    /**
     * @param mixed $requestTarget
     *
     * @return Request
     */
    public function withRequestTarget($requestTarget)
    {
        $clone = clone $this;
        $clone->requestTarget = $requestTarget;

        return $clone;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return Request
     */
    public function withMethod($method)
    {
        $clone = clone $this;
        $clone->method = $method;

        return $clone;
    }

    /**
     * @return UriInterface|void
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param UriInterface $uri
     * @param false        $preserveHost
     *
     * @return Request
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $clone = clone $this;
        $clone->uri = $uri;

        if (!empty($uri->getHost()) && (!$preserveHost || empty($this->getHeader('Host')))) {
            $clone->headers->addHeader('Host', $uri->getHost());

            return $clone;
        }

        return $clone;
    }

    /**
     * @return array
     */
    public function getServerParams()
    {
        return $this->serverParams;
    }

    /**
     * @return array
     */
    public function getCookieParams()
    {
        return $this->cookies;
    }

    /**
     * @param array $cookies
     *
     * @return Request|void
     */
    public function withCookieParams(array $cookies)
    {
        $clone = clone $this;
        $clone->cookies = $cookies;

        return $clone;
    }

    /**
     * @return array
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * @param array $query
     *
     * @return Request
     */
    public function withQueryParams(array $query)
    {
        $clone = clone $this;
        $clone->queryParams = $query;

        return $clone;
    }

    /**
     * @return array
     */
    public function getUploadedFiles()
    {
        return $this->uploadedFiles;
    }

    /**
     * @param array $uploadedFiles
     *
     * @return Request
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        $clone = clone $this;
        $clone->uploadedFiles = $uploadedFiles;

        return $clone;
    }

    /**
     * @return array|object|null
     */
    public function getParsedBody()
    {
        return $this->parsedBody;
    }

    /**
     * @param array|object|null $data
     *
     * @return Request
     */
    public function withParsedBody($data)
    {
        $clone = clone $this;
        $clone->parsedBody = $data;

        return $clone;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     * @param null   $default
     *
     * @return mixed
     */
    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return Request
     */
    public function withAttribute($name, $value)
    {
        $clone =  clone $this;
        $clone->attributes[$name] = $value;

        return $clone;
    }

    /**
     * @param string $name
     *
     * @return Request|void
     */
    public function withoutAttribute($name)
    {
        $clone = clone $this;
        unset($clone->attributes[$name]);

        return $clone;
    }
}
