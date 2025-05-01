<?php

namespace Dust\Http\Responses\Contracts;

interface ErrorResponseHandler
{
    public function handle(\Throwable $e, string $environment): ResponseWrapper;
}
