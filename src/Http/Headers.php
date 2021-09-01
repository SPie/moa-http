<?php

namespace Moa\Http;

use Moa\Http\Contracts\Headers as HeadersContract;

final class Headers implements HeadersContract
{
    /**
     * @var Header[]
     */
    private array $headers = [];

    /**
     * @inheritDoc
     */
    public function setHeader(string $name, $header): self
    {
        $this->headers[$this->normalizeName($name)] = $this->createHeader($name, $this->normalizeValues($header));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addHeader(string $name, $header): self
    {
        $normalizedName = $this->normalizeName($name);
        $header = $this->normalizeValues($header);

        if (!empty($this->headers[$normalizedName])) {
            foreach ($header as $headerValue) {
                $this->headers[$normalizedName]->addValue($headerValue);
            }

            return $this;
        }

        $this->headers[$normalizedName] = $this->createHeader($name, $header);

        return $this;
    }

    private function createHeader(string $name, array $values): Header
    {
        return new Header(
            $name,
            $this->normalizeName($name),
            $values
        );
    }

    /**
     * @param string|array $values
     */
    private function normalizeValues($values): array
    {
        return \is_array($values) ? $values : [$values];
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return \array_map(
            fn (Header $header) => $header->getValues(),
            $this->headers
        );
    }

    /**
     * @inheritDoc
     */
    public function getHeader(string $name): array
    {
        $normalizedName = $this->normalizeName($name);

        return isset($this->headers[$normalizedName])
            ? $this->headers[$normalizedName]->getValues()
            : [];
    }

    public function getHeaderLine(string $name): string
    {
        return \implode(',', $this->getHeader($name));
    }

    public function removeHeader(string $name): self
    {
        unset($this->headers[$this->normalizeName($name)]);

        return $this;
    }

    public function getCookies(): Cookies
    {
        $cookieHeader = $this->getHeader('Cookie');
        if (empty($cookieHeader)) {
            return new Cookies([]);
        }

        $cookieStrings = \preg_split('/;\s/', \reset($cookieHeader));
        $cookies = [];
        foreach ($cookieStrings as $cookie) {
            $cookieParts = \explode('=', $cookie);

            if (\count($cookieParts) === 2) {
                $cookies[$cookieParts[0]] = $cookieParts[1];
            }
        }

        return new Cookies($cookies);
    }

    public function getContentType(): ?string
    {
        $contentType = '';
        foreach ($this->getHeader('Content-Type') as $contentTypeHeader) {
            $contentTypeParts = \explode(';', $contentTypeHeader);
            $contentType = \reset($contentTypeParts);
        }

        return $contentType;
    }

    private function normalizeName(string $name): string
    {
        return \strtolower($name);
    }
}
