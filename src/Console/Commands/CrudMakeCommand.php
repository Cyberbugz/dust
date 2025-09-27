<?php

namespace Dust\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Dust\Console\Core\Concerns\AbsolutePathChecker;

#[AsCommand(name: 'make:crud')]
class CrudMakeCommand extends Command
{
    use AbsolutePathChecker;

    protected $signature = 'make:crud
        {name : Base resource name (e.g., Post)}
        {--M|module= : Name of the module}
        {--G|guard= : Specify environment guard}
        {--A|absolute= : Specify modules absolute path}';

    protected $description = 'Create CRUD stories by calling story:make for each CRUD operation.';

    /** @var array<string> */
    protected array $operations = ['Index', 'Store', 'Show', 'Update', 'Destroy'];

    public function handle(): void
    {
        $this->checkAbsolutePath();

        $name   = $this->argument('name');
        $module = $this->option('module');

        if (! $module) {
            $this->error('Option module is required | --M|module.');
            return;
        }

        $guard = $this->option('guard');

        foreach ($this->operations as $op) {
            $storyName = trim("{$name}{$op}");
            $this->call('make:story', $this->storyArgs($storyName, $module, $guard));
        }

        $this->info('CRUD stories created: ' . implode(', ', array_map(fn($op) => "{$name} {$op}", $this->operations)));
    }

    protected function storyArgs(string $name, string $module, ?string $guard): array
    {
        $args = [
            'name'       => $name,
            '--module'   => $module,
        ];

        if ($guard) {
            $args['--guard'] = $guard;
        }

        if ($abs = $this->option('absolute')) {
            $args['--absolute'] = $abs;
        }

        return $args;
    }

}
