<?php

namespace NormanHuth\NovaPerspectives\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'perspective:create')]
class PerspectiveCreateCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'perspective:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Laravel Nova perspective';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Perspective';

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Nova\Perspectives';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        $path = 'stubs/perspective.stub';
        $appPath = $this->laravel->basePath($path);

        return file_exists($appPath) ? $appPath : dirname(__DIR__, 3) . '/' . $path;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     *
     * @return string
     */
    protected function replaceClass($stub, $name): string
    {
        $class = str_replace($this->getNamespace($name) . '\\', '', $name);
        $label = str_ends_with($class, 'Perspective') && strlen($class) > 11 ? substr($class, 0, -11) : $class;

        $stub = str_replace(['{{ label }}', '{{label}}'], Str::ucfirst($label), $stub);

        return str_replace(['{{ class }}', '{{class}}'], $class, $stub);
    }
}
