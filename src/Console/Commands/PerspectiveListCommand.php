<?php

namespace NormanHuth\NovaPerspectives\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use NormanHuth\NovaPerspectives\Perspective;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Helper\Table;

#[AsCommand(name: 'perspective:list')]
class PerspectiveListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'perspective:list {--basename}';

    /**
     * The perspectives slug's.
     *
     * @var array
     */
    protected array $slugs = [];

    /**
     * Not unique perspectives slug's.
     *
     * @var array
     */
    protected array $notUniqueSlugs = [];

    /**
     * The longest string in the left table column.
     *
     * @var int
     */
    protected int $leftColumnLength = 0;

    /**
     * The longest string in the right table column.
     *
     * @var int
     */
    protected int $rightColumnLength = 0;

    /**
     * @var Collection
     */
    protected Collection $perspectives;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all perspectives including slug';

    /**
     * Execute the console command.
     *
     * @throws ReflectionException
     */
    public function handle()
    {
        $perspectives = [];
        $namespace = app()->getNamespace();

        foreach ((new Finder())->in(config('nova-perspectives.directory'))->files() as $perspective) {
            $perspective = $namespace.str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($perspective->getPathname(), app_path().DIRECTORY_SEPARATOR)
                );

            if (
                is_subclass_of($perspective, Perspective::class) &&
                ! (new ReflectionClass($perspective))->isAbstract()
            ) {
                $perspectives[] = $perspective;
            }
        }

        $this->perspectives = collect($perspectives)
            ->mapWithKeys(function ($perspective) {
                /* @var Perspective|string $perspective */
                $key = $this->option('basename') ? class_basename($perspective) : $perspective;
                return [$key => (new $perspective())->getSlug()];
            })->each(function (string $slug, string $class) {
                if (in_array($slug, $this->slugs)) {
                    $this->notUniqueSlugs[] = $slug;
                } else {
                    $this->slugs[] = $slug;
                }

                $length = strlen($class);
                if ($length > $this->leftColumnLength) {
                    $this->leftColumnLength = $length;
                }

                $length = strlen($slug);
                if ($length > $this->rightColumnLength) {
                    $this->rightColumnLength = $length;
                }
            });

        $info = $this->perspectives->count().' ';
        $info.= $this->perspectives->count() == 1 ? 'Perspective' : 'Perspectives';
        $info.= ' found';
        $this->info($info);

        $this->line('');
        $this->createTable();
        $this->line('');
    }

    /**
     * Write the perspective slugs table.
     *
     * @return void
     */
    protected function createTable(): void
    {
        $table = new Table($this->output);

        $table->setHeaders([
            'Perspective Class', 'Perspective Slug'
        ]);

        $this->perspectives->each(function (string $slug, string $class) use ($table) {
            $slug = !in_array($slug, $this->notUniqueSlugs) ? $slug : '<fg=white;bg=red>'.$slug.'</>';
            $table->addRow([$class, $slug]);
        });

        $table->render();
    }

    /**
     * Write a formatted table border.
     *
     * @return void
     */
    protected function tableBorder(): void
    {
        $this->tableLine('-', '-', '-');
    }

    /**
     * Write a formatted table line.
     *
     * @param string $left
     * @param string $right
     * @param string $padString
     * @return void
     */
    protected function tableLine(string $left, string $right, string $padString = ' '): void
    {
        $line = '|'.$padString;
        $line.= str_pad($left, $this->leftColumnLength, $padString);
        $line.= $padString.'|'.$padString;
        $line.= str_pad($right, $this->rightColumnLength, $padString);
        $line.= $padString.'|';

        $this->line($line);
    }
}
