<?php

namespace Moa\Http\Contracts;

interface HeadersFactory
{
    public function create(): Headers;
}
