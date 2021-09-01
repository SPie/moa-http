<?php

namespace Moa\Http;

final class Cookies
{
    public function __construct(private array $cookies)
    {
    }

    public function cookies(): array
    {
        return $this->cookies;
    }
}
