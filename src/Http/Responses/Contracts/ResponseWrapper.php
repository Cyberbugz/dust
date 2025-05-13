<?php

namespace Dust\Http\Responses\Contracts;

interface ResponseWrapper
{
    public function statusCode(): int;
    public function body(): array;
    public function headers(): array;
}
