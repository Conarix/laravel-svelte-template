<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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

        $softDeletes = in_array(SoftDeletes::class, class_uses($modelClass));

        $records = $modelClass::query()
            ->when($softDeletes, fn ($query) => $query->withTrashed())
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

        $headers = array_map(fn ($col) => str($col)->headline(), $showColumns);
        $rows = $records->getCollection();

        if ($softDeletes) {
            /** @var Collection<array-key, SoftDeletes> $rows */
            $headers[] = 'Deleted';

            $rows->each(fn (Model $row) => $row->setAttribute('deleted', ($row->{$row->getDeletedAtColumn()} ? 'Yes' : 'No')));

            $rows = $rows->select(array_merge($showColumns, ['deleted']))->toArray();
        } else {
            /** @var Collection<array-key, Model> $rows */

            $rows = $rows->select($showColumns)->toArray();
        }

        table(
            headers: $headers,
            rows: $rows,
        );

        // Display number of pages
        \Laravel\Prompts\info("List a different page using the --page option. E.G. art list:model --page=2");
    }
}
