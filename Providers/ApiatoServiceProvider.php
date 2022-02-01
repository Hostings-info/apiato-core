<?php

declare(strict_types=1);

namespace Apiato\Core\Providers;

use Apiato\Core\Abstracts\Providers\MainServiceProvider as AbstractMainServiceProvider;
use Apiato\Core\Foundation\Apiato;
use Apiato\Core\Loaders\AutoLoaderTrait;
use Apiato\Core\Traits\ValidationTrait;
use Illuminate\Support\Facades\Schema;

class ApiatoServiceProvider extends AbstractMainServiceProvider
{
    use AutoLoaderTrait;
    use ValidationTrait;

    /**
     * @var int
     */
    private const DEFAULT_STRING_LENGTH = 191;

    public function boot(): void
    {
        parent::boot();

        // Autoload most of the Containers and Ship Components
        $this->runLoadersBoot();

        /**
         * Solves the "specified key was too long" error, introduced in L5.4.
         *
         * @see https://laravel.com/docs/8.x/migrations#index-lengths-mysql-mariadb
         */
        Schema::defaultStringLength(self::DEFAULT_STRING_LENGTH);

        // Registering custom validation rules
        $this->extendValidationRules();
    }

    public function register(): void
    {
        // NOTE: function order of this calls bellow are important. Do not change it.
        $this->app->bind('Apiato', Apiato::class);

        // Register Core Facade Classes, should not be registered in the $aliases property, since they are used
        // by the auto-loading scripts, before the $aliases property is executed.
        $this->app->alias(Apiato::class, 'Apiato');

        // parent::register() should be called AFTER we bind 'Apiato'
        parent::register();

        // Autoload most of the Containers and Ship Components
        $this->runLoaderRegister();
    }
}
