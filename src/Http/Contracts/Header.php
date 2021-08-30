<?php

namespace Moa\Http\Contracts;

interface Header
{
    public function getOriginalName(): string;

    /**
     * @return string[]
     */
    public function getValue(): array;

    public function getLine(): string;
}
