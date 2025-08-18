<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Finder\Finder;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\select;
use function Laravel\Prompts\table;

class ListModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'list:model {--page=1} {--per-page=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists models in paginated view';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $page = $this->option('page');
        $perPage = $this->option('per-page');

        $models = (new Collection(Finder::create()->files()->depth(0)->in(base_path('app/Models'))))
            ->map(fn ($file) => $file->getBasename('.php'))
            ->filter(fn ($file) => $file !== 'Model') // Remove model abstract from list
            ->sort()
            ->values()
            ->all();

        // Select model
        $modelBasename = select(
            label: "Select a Model to List",
            options: $models,
        );

        /** @var class-string<Model> $modelClass */
        $modelClass = "App\\Models\\{$modelBasename}";

        $records = $modelClass::query()
            ->paginate(
                perPage: $perPage,
                page: $page
            );

        // Select columns
        $availableColumns = Schema::getColumnListing((new $modelClass)->getTable());
        $availableColumns = array_map(
            fn ($column) => str($column)->headline(),
            array_combine(
                array_values($availableColumns),
                array_values($availableColumns),
            )
        );

        $showColumns = multiselect(
            label: "Show Columns",
            options: $availableColumns,
        );

        // Display table
        \Laravel\Prompts\info("Viewing page $page of {$records->lastPage()} for Model $modelBasename");

        table(
            headers: array_map(fn ($col) => str($col)->headline(), $showColumns),
            rows: $records->getCollection()->select($showColumns)->toArray(),
        );

        // Display number of pages
        \Laravel\Prompts\info("List a different page using the --page option. E.G. art list:model --page=2");
    }
}
