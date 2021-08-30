<?php

namespace Moa\Http\Contracts;

interface HeadersBag
{
    /**
     * @param string|string[] $header
     */
    public function setHeader(string $name, $header): self;

    /**
     * @param string|string[] $header
     */
    public function addHeader(string $name, $header): self;

    /**
     * @return string[][]
     */
    public function getHeaders(): array;

    /**
     * @return string[]
     */
    public function getHeader(string $name): array;

    public function getHeaderLine(string $name): string;

    public function removeHeader(string $name): self;
}
