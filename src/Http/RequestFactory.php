<?php

namespace Moa\Http;

use Moa\Http\Contracts\RequestFactory as RequestFactoryContract;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RequestFactory
 *
 * @package Moa\Http
 */
final class RequestFactory implements RequestFactoryContract
{
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        // TODO: Implement createServerRequest() method.
    }
}
