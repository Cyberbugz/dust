<?php

namespace Dust\Http\Responses\Contracts;

use Illuminate\Http\Request;
use Dust\Base\Contracts\RequestHandlerInterface;

interface ErrorLogHandler
{
    public function handle(RequestHandlerInterface $handler, \Throwable $e, Request $request);
    public function addLogObserver(callable $observer): void;
}
