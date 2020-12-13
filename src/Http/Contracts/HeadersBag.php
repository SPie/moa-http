<?php

namespace Moa\Http\Contracts;

/**
 * Interface HeadersBag
 *
 * @package Moa\Http\Contracts
 */
interface HeadersBag
{
    /**
     * @param string          $name
     * @param string|string[] $header
     *
     * @return $this
     */
    public function setHeader(string $name, $header): self;

    /**
     * @param string          $name
     * @param string|string[] $header
     *
     * @return $this
     */
    public function addHeader(string $name, $header): self;

    /**
     * @return string[][]
     */
    public function getHeaders(): array;

    /**
     * @param string $name
     *
     * @return array
     */
    public function getHeader(string $name): array;

    /**
     * @param string $name
     *
     * @return string
     */
    public function getHeaderLine(string $name): string;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function removeHeader(string $name): self;
}
