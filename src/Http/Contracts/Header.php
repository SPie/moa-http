<?php

namespace Moa\Http\Contracts;

/**
 * Interface Header
 *
 * @package Moa\Http\Contracts
 */
interface Header
{
    /**
     * @return string
     */
    public function getOriginalName(): string;

    /**
     * @return string[]
     */
    public function getValue(): array;

    /**
     * @return string
     */
    public function getLine(): string;
}
