<?php

declare(strict_types=1);

namespace Apiato\Core\Abstracts\Providers;

use Apiato\Core\Loaders\MiddlewaresLoaderTrait;
use Illuminate\Contracts\Container\BindingResolutionException;

abstract class MiddlewareServiceProvider extends MainServiceProvider
{
    use MiddlewaresLoaderTrait;

    protected array $middlewares = [];

    protected array $middlewareGroups = [];

    protected array $middlewarePriority = [];

    protected array $routeMiddleware = [];

    /**
     * Perform post-registration booting of services.
     *
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $this->loadMiddlewares();
    }

    /**
     * Register anything in the container.
     */
    public function register(): void
    {
    }
}
