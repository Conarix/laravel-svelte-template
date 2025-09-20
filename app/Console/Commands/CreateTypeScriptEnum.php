<?php

namespace App\Console\Commands;

use App\Enums\AuditTrack\ChangeType;
use BackedEnum;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use SplFileInfo;
use Symfony\Component\Finder\Finder;
use UnitEnum;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\search;

class CreateTypeScriptEnum extends Command
{
    /** @var class-string<UnitEnum>[] */
    const array OMIT_ENUMS = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ts:create-enum {--all} {--force} {--debug} {--file=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Converts a PHP enum to typescript';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('file') && file_exists($this->option('file'))) {
            try {
                $this->handleWatchExecJson();
            } catch (\Throwable $e) {
                $this->error("Watchexec failed with exception: " . $e->getMessage());
                return self::FAILURE;
            }

            return self::SUCCESS;
        } else {
            // Prompt to get Enum
            $enumPath = base_path('app/Enums/');

            $enums = (new Collection(Finder::create()->files()->in($enumPath)))
                ->map(fn (SplFileInfo $file) => str($file->getPathname())->after($enumPath)->toString())
                ->sort()
                ->values();

            if ($this->option('all')) {
                $enumClasses = $enums->all();
            } else {
                $enumClasses = [search(
                    label: 'Select an Enum',
                    options: fn (string $search) => strlen($search) > 0
                        ? $enums->where(fn (string $enum) => str_contains(strtolower($enum), strtolower($search)))->all()
                        : $enums->all()
                )];
            }
        }

        // Strip out .php to allow watchexec to work here
        foreach ($enumClasses as $i => $enumClass) {
            $enumClass = str($enumClass)->unfinish('.php')->replace('/', '\\')->start('App\\Enums\\');

            $this->info("Using $enumClass");

            if (!enum_exists($enumClass)) {
                error("The Enum $enumClass does not exist");
                return self::FAILURE;
            }

            if (in_array($enumClass, self::OMIT_ENUMS)) {
                $this->warn("Enum $enumClass is marked to be omitted");
                return self::FAILURE;
            }

            // Build PHP Enum to TS format
            $baseEnumName = $enumClass->classBasename();

            $this->handleEnumClone($baseEnumName, $enumClass->toString());
        }

        return self::SUCCESS;
    }

    public function handleWatchExecJson(): void
    {
        if (! file_exists($this->option('file'))) {
            throw new \Exception("Failed to find file");
        }

        // Read JSON from file
        $fileContents = file_get_contents($this->option('file'));

        // Each event is new JSON line
        $events = explode("\n", $fileContents);
        $events = array_filter($events);

        foreach ($events as $event) {
            $json = json_decode($event, true);
            $tags = collect($json['tags']);

            $pathEvent = $tags->firstWhere('kind', 'path');

            if ($pathEvent) {
                $enumFile = $pathEvent['absolute'];
                $relativeToEnumsDir = str($enumFile)->after('app/Enums/');

                $enumClass = $relativeToEnumsDir->unfinish('.php')
                    ->replace('/', '\\')
                    ->start('App\\Enums\\');

                $baseName = $enumClass->classBasename();

                $this->handleEnumClone($baseName, $enumClass->toString());
            }
        }
    }

    /**
     * @param string $baseName
     * @param class-string<BackedEnum> $enumClass
     * @return int
     */
    public function handleEnumClone(string $baseName, string $enumClass): int
    {
        $tsEnum = <<< END_OF_ENUM
        export enum {$baseName}Enum {
        END_OF_ENUM;

        /** @var BackedEnum $enum */
        foreach ($enumClass::cases() as $enum) {
            $tsEnum .= "\n\t$enum->name = '{$enum->value}',";
        }

        $tsEnum .= "\n}";

        $this->info("Converted $enumClass to:\n$tsEnum");

        if (!$this->option('force')) {
            $confirmed = confirm(
                label: "Is this correct?",
            );

            if (!$confirmed) {
                error("Aborting conversion");
                return self::FAILURE;
            }
        }

        // Use regex to replace in resources/js/types/enums.ts

        $enumsTsPath = resource_path('js/types/enums.ts');
        $enumsTsContent = file_get_contents($enumsTsPath);

        $regex = $this->getEnumRegex($baseName);

        if (
            preg_match($regex, $enumsTsContent, $matches) === 1
        ) {
            $newEnumsTsContent = preg_replace(
                $regex,
                $tsEnum,
                $enumsTsContent
            );
        } else {
            $newEnumsTsContent = $enumsTsContent . "\n$tsEnum";
        }

        if ($this->isDebug()) {
            $this->info("New enums.ts contents:\n$newEnumsTsContent");
        }

        file_put_contents($enumsTsPath, $newEnumsTsContent);

        $this->info("$enumsTsPath updated with new contents");

        return self::SUCCESS;
    }

    private function getEnumRegex($enumName): string
    {
        return "/^export\s+enum\s+{$enumName}Enum[^}]*}/m";
    }

    private function isDebug(): bool
    {
        return $this->option('debug');
    }
}
