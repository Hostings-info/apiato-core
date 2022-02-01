<?php

declare(strict_types=1);

namespace Apiato\Core\Generator\Commands;

use Apiato\Core\Generator\GeneratorCommand;
use Apiato\Core\Generator\Interfaces\ComponentsGenerator;
use Apiato\Core\Generator\Traits\UIGeneratorTrait;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ContainerWebGenerator extends GeneratorCommand implements ComponentsGenerator
{
    use UIGeneratorTrait;

    /**
     * User required/optional inputs expected to be passed while calling the command.
     * This is a replacement of the `getArguments` function "which reads whenever it's called".
     */
    public array $inputs = [
        ['url', null, InputOption::VALUE_OPTIONAL, 'The base URI of all endpoints (/stores, /cars, ...)'],
        ['controllertype', null, InputOption::VALUE_OPTIONAL, 'The controller type (SAC, MAC)'],
        ['transporters', null, InputOption::VALUE_OPTIONAL, 'Use specific Transporters'],
    ];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'apiato:generate:container:web';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Container for apiato from scratch (WEB Part)';

    /**
     * The type of class being generated.
     */
    protected string $fileType = 'Container';

    /**
     * The structure of the file path.
     */
    protected string $pathStructure = '{section-name}/{container-name}/*';

    /**
     * The structure of the file name.
     */
    protected string $nameStructure = '{file-name}';

    /**
     * The name of the stub file.
     */
    protected string $stubName = 'composer.stub';

    public function getUserInputs(): array
    {
        $ui = 'web';

        [
            $useTransporters,
            $sectionName,
            $_sectionName,
            $containerName,
            $_containerName,
            $model,
            $models,
        ] = $this->runCallParam();

        // Create the default routes for this container
        $this->printInfoMessage('Generating Default Routes');
        $version = 1;
        $doctype = 'private';

        // Get the URI and remove the first trailing slash
        $url = Str::lower($this->checkParameterOrAsk('url', 'Enter the base URI for all WEB endpoints (foo/bar)', Str::lower($models)));
        $url = ltrim($url, '/');

        $controllertype = Str::lower($this->checkParameterOrChoice('controllertype', 'Select the controller type (Single or Multi Action Controller)', ['SAC', 'MAC'], 0));

        $this->printInfoMessage('Generating Requests for Routes');
        $this->printInfoMessage('Generating Default Actions');
        $this->printInfoMessage('Generating Default Tasks');
        $this->printInfoMessage('Generating Default Controller/s');

        $routes = [
            [
                'stub'        => 'GetAll',
                'name'        => 'GetAll' . $models,
                'operation'   => 'index',
                'verb'        => 'GET',
                'url'         => $url,
                'action'      => 'GetAll' . $models . 'Action',
                'request'     => 'GetAll' . $models . 'Request',
                'task'        => 'GetAll' . $models . 'Task',
                'controller'  => 'GetAll' . $models . 'Controller',
                'transporter' => 'GetAll' . $models . 'Transporter',
            ],
            [
                'stub'        => 'Find',
                'name'        => 'Find' . $model . 'ById',
                'operation'   => 'show',
                'verb'        => 'GET',
                'url'         => $url . '/{id}',
                'action'      => 'Find' . $model . 'ById' . 'Action',
                'request'     => 'Find' . $model . 'ById' . 'Request',
                'task'        => 'Find' . $model . 'ById' . 'Task',
                'controller'  => 'Find' . $model . 'ById' . 'Controller',
                'transporter' => 'Find' . $model . 'ById' . 'Transporter',
            ],
            [
                'stub'        => 'Create',
                'name'        => 'Store' . $model,
                'operation'   => 'store',
                'verb'        => 'POST',
                'url'         => $url . '/store',
                'action'      => 'Create' . $model . 'Action',
                'request'     => 'Store' . $model . 'Request',
                'task'        => 'Create' . $model . 'Task',
                'controller'  => 'Create' . $model . 'Controller',
                'transporter' => 'Create' . $model . 'Transporter',
            ],
            [
                'stub'        => 'Store',
                'name'        => 'Create' . $model,
                'operation'   => 'create',
                'verb'        => 'GET',
                'url'         => $url . '/create',
                'action'      => null,
                'request'     => 'Create' . $model . 'Request',
                'task'        => null,
                'controller'  => 'Create' . $model . 'Controller',
                'transporter' => null,
            ],
            [
                'stub'        => 'Update',
                'name'        => 'Update' . $model,
                'operation'   => 'update',
                'verb'        => 'PATCH',
                'url'         => $url . '/{id}',
                'action'      => 'Update' . $model . 'Action',
                'request'     => 'Update' . $model . 'Request',
                'task'        => 'Update' . $model . 'Task',
                'controller'  => 'Update' . $model . 'Controller',
                'transporter' => 'Update' . $model . 'Transporter',
            ],
            [
                'stub'        => 'Edit',
                'name'        => 'Edit' . $model,
                'operation'   => 'edit',
                'verb'        => 'GET',
                'url'         => $url . '/{id}/edit',
                'action'      => null,
                'request'     => 'Edit' . $model . 'Request',
                'task'        => null,
                'controller'  => 'Update' . $model . 'Controller',
                'transporter' => null,
            ],
            [
                'stub'        => 'Delete',
                'name'        => 'Delete' . $model,
                'operation'   => 'destroy',
                'verb'        => 'DELETE',
                'url'         => $url . '/{id}',
                'action'      => 'Delete' . $model . 'Action',
                'request'     => 'Delete' . $model . 'Request',
                'task'        => 'Delete' . $model . 'Task',
                'controller'  => 'Delete' . $model . 'Controller',
                'transporter' => 'Delete' . $model . 'Transporter',
            ],
        ];

        foreach ($routes as $route) {
            $enableTransporter = false;

            if ($useTransporters && $route['transporter'] !== null) {
                $enableTransporter = true;
            }

            $this->call('apiato:generate:request', [
                '--section'         => $sectionName,
                '--container'       => $containerName,
                '--file'            => $route['request'],
                '--ui'              => $ui,
                '--stub'            => $route['stub'],
                '--transporter'     => $enableTransporter,
                '--transportername' => $route['transporter'],
            ]);

            if ($route['action'] !== null) {
                $this->call('apiato:generate:action', [
                    '--section'   => $sectionName,
                    '--container' => $containerName,
                    '--file'      => $route['action'],
                    '--ui'        => $ui,
                    '--model'     => $model,
                    '--stub'      => $route['stub'],
                ]);
            }

            if ($route['task'] !== null) {
                $this->call('apiato:generate:task', [
                    '--section'   => $sectionName,
                    '--container' => $containerName,
                    '--file'      => $route['task'],
                    '--model'     => $model,
                    '--stub'      => $route['stub'],
                ]);
            }

            // Finally, generate the controller
            if ($controllertype === 'sac') {
                $this->call('apiato:generate:route', [
                    '--section'    => $sectionName,
                    '--container'  => $containerName,
                    '--file'       => $route['name'],
                    '--ui'         => $ui,
                    '--operation'  => $route['operation'],
                    '--doctype'    => $doctype,
                    '--docversion' => $version,
                    '--url'        => $route['url'],
                    '--verb'       => $route['verb'],
                    '--controller' => $route['controller'],
                ]);

                $this->call('apiato:generate:controller', [
                    '--section'   => $sectionName,
                    '--container' => $containerName,
                    '--file'      => $route['controller'],
                    '--ui'        => $ui,
                    '--stub'      => $route['stub'],
                ]);
            } else {
                $this->call('apiato:generate:route', [
                    '--section'    => $sectionName,
                    '--container'  => $containerName,
                    '--file'       => $route['name'],
                    '--ui'         => $ui,
                    '--operation'  => $route['operation'],
                    '--doctype'    => $doctype,
                    '--docversion' => $version,
                    '--url'        => $route['url'],
                    '--verb'       => $route['verb'],
                    '--controller' => 'Controller',
                ]);
            }
        }

        if ($controllertype === 'mac') {
            $this->printInfoMessage('Generating Controller to wire everything together');
            $this->call('apiato:generate:controller', [
                '--section'   => $sectionName,
                '--container' => $containerName,
                '--file'      => 'Controller',
                '--ui'        => $ui,
                '--stub'      => 'crud',
            ]);
        }

        $this->printInfoMessage('Generating Composer File');

        return [
            'path-parameters' => [
                'section-name'   => $this->sectionName,
                'container-name' => $this->containerName,
            ],
            'stub-parameters' => [
                '_section-name'   => $_sectionName,
                'section-name'    => $this->sectionName,
                '_container-name' => $_containerName,
                'container-name'  => $containerName,
                'class-name'      => $this->fileName,
            ],
            'file-parameters' => [
                'file-name' => $this->fileName,
            ],
        ];
    }

    /**
     * Get the default file name for this component to be generated.
     */
    public function getDefaultFileName(): string
    {
        return 'composer';
    }

    public function getDefaultFileExtension(): string
    {
        return 'json';
    }
}
