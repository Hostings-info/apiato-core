<?php

declare(strict_types=1);

namespace Apiato\Core\Generator;

use Apiato\Core\Generator\Commands\ActionGenerator;
use Apiato\Core\Generator\Commands\ConfigurationGenerator;
use Apiato\Core\Generator\Commands\ContainerApiGenerator;
use Apiato\Core\Generator\Commands\ContainerGenerator;
use Apiato\Core\Generator\Commands\ContainerWebGenerator;
use Apiato\Core\Generator\Commands\ControllerGenerator;
use Apiato\Core\Generator\Commands\EventGenerator;
use Apiato\Core\Generator\Commands\EventHandlerGenerator;
use Apiato\Core\Generator\Commands\EventListenerGenerator;
use Apiato\Core\Generator\Commands\ExceptionGenerator;
use Apiato\Core\Generator\Commands\JobGenerator;
use Apiato\Core\Generator\Commands\MailGenerator;
use Apiato\Core\Generator\Commands\MiddlewareGenerator;
use Apiato\Core\Generator\Commands\MigrationGenerator;
use Apiato\Core\Generator\Commands\ModelFactoryGenerator;
use Apiato\Core\Generator\Commands\ModelGenerator;
use Apiato\Core\Generator\Commands\NotificationGenerator;
use Apiato\Core\Generator\Commands\ReadmeGenerator;
use Apiato\Core\Generator\Commands\RepositoryGenerator;
use Apiato\Core\Generator\Commands\RequestGenerator;
use Apiato\Core\Generator\Commands\RouteGenerator;
use Apiato\Core\Generator\Commands\SeederGenerator;
use Apiato\Core\Generator\Commands\ServiceProviderGenerator;
use Apiato\Core\Generator\Commands\SubActionGenerator;
use Apiato\Core\Generator\Commands\TaskGenerator;
use Apiato\Core\Generator\Commands\TestFunctionalTestGenerator;
use Apiato\Core\Generator\Commands\TestTestCaseGenerator;
use Apiato\Core\Generator\Commands\TestUnitTestGenerator;
use Apiato\Core\Generator\Commands\TransformerGenerator;
use Apiato\Core\Generator\Commands\TransporterGenerator;
use Apiato\Core\Generator\Commands\ValueGenerator;
use Illuminate\Support\ServiceProvider;

class GeneratorsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->registerGenerators();
    }

    /**
     * Register the generators.
     */
    private function registerGenerators(): void
    {
        $classes = [
            ActionGenerator::class,
            ConfigurationGenerator::class,
            ContainerGenerator::class,
            ContainerApiGenerator::class,
            ContainerWebGenerator::class,
            ControllerGenerator::class,
            EventGenerator::class,
            EventHandlerGenerator::class,
            EventListenerGenerator::class,
            ExceptionGenerator::class,
            JobGenerator::class,
            ModelFactoryGenerator::class,
            MailGenerator::class,
            MiddlewareGenerator::class,
            MigrationGenerator::class,
            ModelGenerator::class,
            NotificationGenerator::class,
            ReadmeGenerator::class,
            RepositoryGenerator::class,
            RequestGenerator::class,
            RouteGenerator::class,
            SeederGenerator::class,
            ServiceProviderGenerator::class,
            SubActionGenerator::class,
            TestFunctionalTestGenerator::class,
            TestTestCaseGenerator::class,
            TestUnitTestGenerator::class,
            TaskGenerator::class,
            TransformerGenerator::class,
            ValueGenerator::class,
            TransporterGenerator::class,
        ];

        foreach ($classes as $class) {
            $lowerClass = strtolower($class);

            $this->app->singleton(sprintf('command.porto.%s', $lowerClass), fn ($app) => $app[$class]);

            $this->commands(sprintf('command.porto.%s', $lowerClass));
        }
    }
}
