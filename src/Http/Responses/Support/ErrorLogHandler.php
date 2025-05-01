<?php

namespace Dust\Http\Responses\Support;

use Throwable;
use Dust\Support\Logger;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use Dust\Base\Contracts\RequestHandlerInterface;

class ErrorLogHandler implements \Dust\Http\Responses\Contracts\ErrorLogHandler
{
    private array $observers = [];

    public function handle(RequestHandlerInterface $handler, Throwable $e, Request $request)
    {
        Logger::error(
            sprintf('%s_ERROR',
                $this->getSnakedName(
                    $this->getClassName($handler),
                ),
            ),
            $e,
            $this->buildLogBody($request, $e),
        );
    }

    public function addLogObserver(callable $observer): void
    {
        $this->observers[] = $observer;
    }

    final protected function getSnakedName(string $name): string
    {
        return strtoupper(
            implode('_',
                array_filter(
                    preg_split('/(?=[A-Z])/', $name),
                ),
            ),
        );
    }

    final protected function getClassName(RequestHandlerInterface $handler): string
    {
        $namespace = explode('\\', get_class($handler));

        return array_pop($namespace);
    }

    final protected function buildLogBody(Request $request, Throwable $e): array
    {
        $body = [];

        foreach ($this->observers as $observer) {
            $body = array_merge($body, $observer($request, $e));
        }

        return array_merge($body, $this->errorMeta($request->user(), $e));
    }

    protected function errorMeta(?Authenticatable $user, Throwable $e): array
    {
        return [
            'user' => $user?->getAuthIdentifier(),
            'exception' => [
                'class' => get_class($e),
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ],
        ];
    }
}
