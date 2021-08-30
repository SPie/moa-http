<?php

namespace Moa\Http;

use Moa\Http\Contracts\HeadersBag;
use Moa\Http\Contracts\Request as RequestContract;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

final class Request implements RequestContract
{
    private string $method;

    private UriInterface $uri;

    private HeadersBag $headers;

    private array $cookies;

    private StreamInterface $body;

    private array $serverParams;

    private array $uploadedFiles;

    private string $protocolVersion;

    private ?string $requestTarget;

    private array $queryParams;

    private array $attributes;

    private ?array $parsedBody;

    public function __construct(
        string $method,
        UriInterface $uri,
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
     * @inheritDoc
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion($version)
    {
        $clone = clone $this;
        $clone->protocolVersion = $version;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders()
    {
        return $this->headers->getHeaders();
    }

    /**
     * @inheritDoc
     */
    public function hasHeader($name)
    {
        return !empty($this->headers->getHeader($name));
    }

    /**
     * @inheritDoc
     */
    public function getHeader($name)
    {
        return $this->headers->getHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine($name)
    {
        return $this->headers->getHeaderLine($name);
    }

    /**
     * @inheritDoc
     */
    public function withHeader($name, $value)
    {
        $clone = clone $this;
        $clone->headers->setHeader($name, $value);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader($name, $value)
    {
        $clone = clone $this;
        $clone->headers->addHeader($name, $value);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader($name)
    {
        $clone = clone $this;
        $clone->headers->removeHeader($name);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body)
    {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function withRequestTarget($requestTarget)
    {
        $clone = clone $this;
        $clone->requestTarget = $requestTarget;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @inheritDoc
     */
    public function withMethod($method)
    {
        $clone = clone $this;
        $clone->method = $method;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function getServerParams()
    {
        return $this->serverParams;
    }

    /**
     * @inheritDoc
     */
    public function getCookieParams()
    {
        return $this->cookies;
    }

    /**
     * @inheritDoc
     */
    public function withCookieParams(array $cookies)
    {
        $clone = clone $this;
        $clone->cookies = $cookies;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * @inheritDoc
     */
    public function withQueryParams(array $query)
    {
        $clone = clone $this;
        $clone->queryParams = $query;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getUploadedFiles()
    {
        return $this->uploadedFiles;
    }

    /**
     * @inheritDoc
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        $clone = clone $this;
        $clone->uploadedFiles = $uploadedFiles;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getParsedBody()
    {
        return $this->parsedBody;
    }

    /**
     * @inheritDoc
     */
    public function withParsedBody($data)
    {
        $clone = clone $this;
        $clone->parsedBody = $data;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @inheritDoc
     */
    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function withAttribute($name, $value)
    {
        $clone =  clone $this;
        $clone->attributes[$name] = $value;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withoutAttribute($name)
    {
        $clone = clone $this;
        unset($clone->attributes[$name]);

        return $clone;
    }
}
