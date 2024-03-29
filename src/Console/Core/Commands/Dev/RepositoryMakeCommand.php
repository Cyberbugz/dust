<?php

namespace Dust\Console\Core\Commands\Dev;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Dust\Console\Core\Concerns\ModelQualifier;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Dust\Console\Core\Concerns\AbsolutePathChecker;

#[AsCommand(name: 'make:repository')]
class RepositoryMakeCommand extends GeneratorCommand
{
    use AbsolutePathChecker, ModelQualifier;

    protected $name = 'make:repository';

    protected $description = 'Create a new model repository';

    protected $type = 'Repository';

    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/repository.stub');
    }

    protected function buildClass($name): array|string
    {
        $repository = class_basename(Str::ucfirst(str_replace('Repository', '', $name)));

        $namespaceModel = $this->qualifyModel($this->option('model'));

        $model = class_basename($namespaceModel);

        $namespace = get_module_namespace($this->rootNamespace(), $this->option('module'), [
            'Domain', 'Repositories',
        ]);

        $replace = [
            '{{ repositoryNamespace }}' => $namespace,
            'NamespacedDummyModel' => $namespaceModel,
            '{{ namespacedModel }}' => $namespaceModel,
            '{{namespacedModel}}' => $namespaceModel,
            'DummyModel' => $model,
            '{{ model }}' => $model,
            '{{ modelVariable }}' => lcfirst($model),
            '{{model}}' => $model,
            '{{ repository }}' => $repository,
            '{{repository}}' => $repository,
        ];

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name),
        );
    }

    protected function getPath($name): string
    {
        $module = $this->option('module');
        $name = (string) Str::of($name)->replaceFirst(get_module_namespace($this->laravel->getNamespace(), $module, [
            'Domain', 'Repositories',
        ]), '')->finish('Repository');
        if (str_starts_with($name, '\\')) {
            $name = str_replace('\\', '', $name);
        }

        return get_module_path($module, ['Domain', 'Repositories', "$name.php"]);
    }

    protected function guessModelName($name): array|string
    {
        if (str_ends_with($name, 'Repository')) {
            $name = substr($name, 0, -7);
        }

        $modelName = $this->qualifyModel(Str::after($name, $this->rootNamespace()));

        if (class_exists($modelName)) {
            return $modelName;
        }

        $names = explode('\\', $modelName);

        $modelName = array_pop($names);

        return get_module_namespace($this->rootNamespace(), $this->option('module'), [
            'Domain', 'Entities', $modelName,
        ]);
    }

    protected function getOptions(): array
    {
        return array_merge(parent::getOptions(), [
            ['model', 'm', InputOption::VALUE_REQUIRED, 'The name of the model'],
            ['module', 'M', InputOption::VALUE_REQUIRED, 'The name of the module'],
        ]);
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return get_module_namespace($this->laravel->getNamespace(), $this->option('module'),
            [
                'Domain',
                'Repositories',
            ],
        );
    }
}
