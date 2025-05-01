<?php

namespace Dust\Http\Responses;

use Illuminate\Http\JsonResponse;
use Dust\Http\Responses\Contracts\ResponseWrapper;
use Dust\Http\Responses\Contracts\ErrorResponseHandler;
use Dust\Http\Responses\Support\DefaultResponseWrapper;

class ErrorResponse extends JsonResponse
{
    public function __construct(
        \Throwable $e,
        ?string $environment,
        ?ErrorResponseHandler $handler = null,
    ) {
        $this->init($e, $environment, $handler);
    }

    protected function init(\Throwable $e, ?string $environment, ?ErrorResponseHandler $handler): void
    {
        $responseWrapper = $handler ? $handler->handle($e, $environment) : $this->getDefaultResponseWrapper($e, $environment);

        parent::__construct($responseWrapper->body(), $responseWrapper->statusCode(), $responseWrapper->headers());
    }

    private function getDefaultResponseWrapper(\Throwable $e, ?string $environment): ResponseWrapper
    {
        return new DefaultResponseWrapper($e, $environment);
    }
}
