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
     * @var Uri
     */
    private Uri $uri;

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
     * Request constructor.
     *
     * @param string          $method
     * @param Uri             $uri
     * @param HeadersBag      $headers
     * @param array           $cookies
     * @param StreamInterface $body
     * @param array           $serverParams
     * @param array           $uploadedFiles
     */
    public function __construct(
        string $method,
        Uri $uri,
        HeadersBag $headers,
        array $cookies,
        StreamInterface $body,
        array $serverParams = [],
        array $uploadedFiles = []
    ) {
        $this->method        = $method;
        $this->uri           = $uri;
        $this->headers       = $headers;
        $this->cookies       = $cookies;
        $this->body          = $body;
        $this->serverParams  = $serverParams;
        $this->uploadedFiles = $uploadedFiles;

        $this->protocolVersion = !empty($this->serverParams['SERVER_PROTOCOL'])
            ? $this->protocolVersion = \str_replace('HTTP/', '', $this->serverParams['SERVER_PROTOCOL'])
            : '1.1';

        $this->requestTarget = null;
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
     * @return string|void
     */
    public function getMethod()
    {
        // TODO: Implement getMethod() method.
    }

    /**
     * @param string $method
     *
     * @return Request|void
     */
    public function withMethod($method)
    {
        // TODO: Implement withMethod() method.
    }

    /**
     * @return UriInterface|void
     */
    public function getUri()
    {
        // TODO: Implement getUri() method.
    }

    /**
     * @param UriInterface $uri
     * @param false        $preserveHost
     *
     * @return Request|void
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        // TODO: Implement withUri() method.
    }

    /**
     * @return array|void
     */
    public function getServerParams()
    {
        // TODO: Implement getServerParams() method.
    }

    /**
     * @return array|void
     */
    public function getCookieParams()
    {
        // TODO: Implement getCookieParams() method.
    }

    /**
     * @param array $cookies
     *
     * @return Request|void
     */
    public function withCookieParams(array $cookies)
    {
        // TODO: Implement withCookieParams() method.
    }

    /**
     * @return array|void
     */
    public function getQueryParams()
    {
        // TODO: Implement getQueryParams() method.
    }

    /**
     * @param array $query
     *
     * @return Request|void
     */
    public function withQueryParams(array $query)
    {
        // TODO: Implement withQueryParams() method.
    }

    /**
     * @return array|void
     */
    public function getUploadedFiles()
    {
        // TODO: Implement getUploadedFiles() method.
    }

    /**
     * @param array $uploadedFiles
     *
     * @return Request|void
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        // TODO: Implement withUploadedFiles() method.
    }

    /**
     * @return array|object|void|null
     */
    public function getParsedBody()
    {
        // TODO: Implement getParsedBody() method.
    }

    /**
     * @param array|object|null $data
     *
     * @return Request|void
     */
    public function withParsedBody($data)
    {
        // TODO: Implement withParsedBody() method.
    }

    /**
     * @return array|void
     */
    public function getAttributes()
    {
        // TODO: Implement getAttributes() method.
    }

    /**
     * @param string $name
     * @param null   $default
     *
     * @return mixed|void
     */
    public function getAttribute($name, $default = null)
    {
        // TODO: Implement getAttribute() method.
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return Request|void
     */
    public function withAttribute($name, $value)
    {
        // TODO: Implement withAttribute() method.
    }

    /**
     * @param string $name
     *
     * @return Request|void
     */
    public function withoutAttribute($name)
    {
        // TODO: Implement withoutAttribute() method.
    }
}
