<?php

namespace Dust\Http\Responses\Support;

use Throwable;
use Dust\Http\Responses\Contracts\ResponseWrapper;

class DefaultResponseWrapper implements ResponseWrapper
{
    public const DEFAULT_STATUS_CODE = 500;
    public const DEFAULT_MESSAGE = 'Something went wrong! Please try again later.';

    public function __construct(
        protected Throwable $e,
        protected ?string $environment,
    ) {
    }

    public function statusCode(): int
    {
        $code = $this->e->getCode();

        if ($code >= 400 && $code <= 500) {
            return $code;
        }

        return self::DEFAULT_STATUS_CODE;
    }

    public function body(): array
    {
        $body = [
            'message' => !$this->isProduction() ? $this->e->getMessage() : self::DEFAULT_MESSAGE,
            'data'    => null,
            'errors'  => $this->getErrorList(),
        ];

        if (!$this->isProduction()) {
            $body['trace'] = [
                'exception' => get_class($this->e),
                'file'      => $this->e->getFile(),
                'line'      => $this->e->getLine(),
            ];
        }

        return $body;
    }

    public function headers(): array
    {
        return [];
    }

    private function isProduction(): bool
    {
        return $this->environment === 'production';
    }

    /**
     * @return array[]
     */
    public function getErrorList(): array
    {
        if ($this->isProduction()) {
            return [];
        }

        return method_exists($this->e, 'errors') ? $this->e->errors() : [
            ['message' => $this->e->getMessage(), 'code' => $this->e->getCode()]
        ];
    }
}
