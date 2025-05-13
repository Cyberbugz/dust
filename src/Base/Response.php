<?php

namespace Dust\Base;

use Closure;
use Throwable;
use Dust\Support\Logger;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Dust\Http\Responses\ErrorResponse;
use Illuminate\Foundation\Application;
use Dust\Base\Contracts\ResponseInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Dust\Base\Contracts\RequestHandlerInterface;
use Dust\Http\Responses\Support\ErrorLogHandler;
use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Dust\Http\Responses\Contracts\ErrorLogHandler as ErrorLogHandlerInterface;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Database\MultipleRecordsFoundException;
use Dust\Http\Responses\Contracts\ErrorResponseHandler;
use Dust\Base\Contracts\RestrictEventInjectionInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Dust\Exceptions\Response\EventInjectionRestrictedException;
use Illuminate\Routing\Exceptions\BackedEnumCaseNotFoundException;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;

abstract class Response implements ResponseInterface
{
    protected const LARAVEL_HANDLED_EXCEPTIONS = [
        AuthenticationException::class,
        AuthorizationException::class,
        BackedEnumCaseNotFoundException::class,
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        MultipleRecordsFoundException::class,
        RecordsNotFoundException::class,
        SuspiciousOperationException::class,
        TokenMismatchException::class,
        ValidationException::class,
    ];

    private static ?ErrorResponseHandler $errorResponseHandler = null;
    private static ?ErrorLogHandlerInterface $errorLogHandler = null;

    /**
     * @var callable[]
     */
    private array $logObservers = [];

    /**
     * @var callable[]
     */
    private array $onSuccessHandlers = [];

    /**
     * @var callable[]
     */
    private array $onFailureHandlers = [];

    private bool $silence = false;

    private ?Request $request = null;

    private static bool $enableLogging = true;

    private static bool $laravelCanHandleItsExceptions = true;

    private static array $mandatoryLaravelHandledExceptions = [];

    public function __construct(protected Application $app)
    {
    }

    /**
     * @throws Throwable
     */
    public function send(RequestHandlerInterface $handler, Request $request): mixed
    {
        $this->request = $request;
        try {
            $data = call_user_func([$handler, 'handle'], $this, $request);
            $this->fireSuccessChain($data);

            return $this->createResource($data);
        } catch (Throwable $e) {
            $handled = $this->isLaravelHandledException($e);

            if (!$handled) {
                $this->logError($handler, $request, $e);
            }

            $this->fireFailureChain($request, $e);

            return $this->error($request, $e, $handled);
        }
    }

    protected function getRequest(): ?Request
    {
        return $this->request;
    }

    public static function setErrorResponseHandler(ErrorResponseHandler $handler): void
    {
        self::$errorResponseHandler = $handler;
    }

    public static function enableLogging(): void
    {
        self::$enableLogging = true;
    }

    public static function disableLogging(): void
    {
        self::$enableLogging = false;
    }

    public static function setErrorLogHandler(ErrorLogHandlerInterface $handler): void
    {
        self::$errorLogHandler = $handler;
    }

    public static function enableLaravelCanHandleItsExceptions(): void
    {
        self::$laravelCanHandleItsExceptions = true;
    }

    public static function disableLaravelCanHandleItsExceptions(): void
    {
        self::$laravelCanHandleItsExceptions = false;
    }

    public static function addMandatoryLaravelHandledException(string $exception): void
    {
        self::$mandatoryLaravelHandledExceptions[] = $exception;
    }

    protected function canLaravelHandleItsExceptions(): bool
    {
        return self::$laravelCanHandleItsExceptions;
    }

    final public function silent(): static
    {
        $this->silence = true;

        return $this;
    }

    /**
     * @throws EventInjectionRestrictedException
     */
    final public function onSuccess(callable $handler): static
    {
        if ($this instanceof RestrictEventInjectionInterface) {
            throw new EventInjectionRestrictedException($this);
        }
        $this->onSuccessHandlers[] = $handler;

        return $this;
    }

    /**
     * @throws EventInjectionRestrictedException
     */
    final public function onFailure(callable $handler): static
    {
        if ($this instanceof RestrictEventInjectionInterface) {
            throw new EventInjectionRestrictedException($this);
        }

        $this->onFailureHandlers[] = $handler;

        return $this;
    }

    final public function onLog(Closure $handler): static
    {
        $this->logObservers[] = $handler;

        return $this;
    }

    final protected function logError(RequestHandlerInterface $handler, Request $request, Throwable $e): void
    {
        if (! self::$enableLogging) {
            return;
        }

        $logHandler = self::$errorLogHandler ?: $this->defaultLogHandler();

        if (!empty($this->logObservers)) {
            foreach ($this->logObservers as $observer) {
                $logHandler->addLogObserver($observer);
            }
        }

        $logHandler->handle(
            $handler,
            $e,
            $request,
        );
    }

    final protected function defaultLogHandler(): ErrorLogHandlerInterface
    {
        return new ErrorLogHandler();
    }

    final protected function fireSuccessChain(mixed $resource): void
    {
        if ($this->silence) {
            return;
        }

        foreach ($this->onSuccessHandlers as $handler) {
            $handler($resource);
        }

        $this->success($resource);
    }

    final protected function fireFailureChain(Request $request, ?Throwable $e = null): void
    {
        if ($this->silence) {
            return;
        }

        foreach ($this->onFailureHandlers as $handler) {
            $handler($request, $e);
        }

        $this->failure($request, $e);
    }

    /**
     * @throws Throwable
     */
    final protected function error(Request $request, Throwable $e, bool $handled = false): JsonResponse|View
    {
        return $this->handleErrorResponse($e) ?: $this->defaultErrorResponse($request, $e, $handled);
    }

    /**
     * @throws Throwable
     */
    final protected function defaultErrorResponse(
        Request $request,
        Throwable $e,
        bool $handled,
    ): JsonResponse|ErrorResponse|View {
        if ($handled) {
            throw $e;
        }

        if (
            !$request->expectsJson()
        ) {
            $errorView = $this->app['config']->get('dust.default_error_view');

            $view = $this->app['view'];
            if ($view->exists($errorView)) {
                return $view->make($errorView, ['exception' => $e]);
            }
        }

        return new ErrorResponse($e, $this->getEnvironment(), self::$errorResponseHandler);
    }

    final protected function isMandatoryLaravelHandledException(Throwable $e): bool
    {
        foreach (self::$mandatoryLaravelHandledExceptions as $ex) {
            if ($e instanceof $ex) {
                return true;
            }
        }

        return false;
    }

    protected function isLaravelHandledException(Throwable $e): bool
    {
        if ($this->isMandatoryLaravelHandledException($e)) {
            return true;
        }

        return $this->canLaravelHandleItsExceptions() && $this->isStandardLaravelException($e);
    }

    private function isStandardLaravelException(Throwable $e): bool
    {
        foreach (self::LARAVEL_HANDLED_EXCEPTIONS as $ex) {
            if ($e instanceof $ex) {
                return true;
            }
        }

        return false;
    }

    final protected function getEnvironment(): string
    {
        return $this->app->config['app']['env'];
    }

    protected function success(mixed $resource): void
    {
        //
    }

    protected function failure(Request $request, ?Throwable $e): void
    {
        //
    }

    protected function handleErrorResponse(Throwable $e): false|JsonResponse|View
    {
        return false;
    }

    abstract protected function createResource(mixed $resource): mixed;
}
