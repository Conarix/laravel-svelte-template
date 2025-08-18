<?php

namespace App\Console\Commands;

use BackedEnum;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Collection;
use Illuminate\Support\Stringable;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;
use Symfony\Component\Finder\Finder;
use UnitEnum;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\suggest;

class CreateTypeScriptEnum extends Command
{
    /** @var class-string<UnitEnum>[] */
    const array OMIT_ENUMS = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ts:create-enum {enum?} {--all} {--force} {--debug}';

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
        if ($this->argument('enum')) {
            // Ensure given enum exists
            $enumClasses = [$this->argument('enum')];
        } else {
            // Prompt to get Enum
            $enumPath = base_path('app/Enums/');

            $enums = (new Collection(Finder::create()->files()->depth(0)->in($enumPath)))
                ->map(fn ($file) => $file->getBasename('.php'))
                ->sort()
                ->values()
                ->all();

            if ($this->option('all')) {
                $enumClasses = $enums;
            } else {
                $enumClasses = [suggest(
                    label: 'Select an Enum',
                    options: $enums,
                )];
            }
        }

        // Strip out .php to allow watchexec to work here
        foreach ($enumClasses as $enumClass) {
            $enumClass = str($enumClass)->unfinish('.php')->start('App\\Enums\\');

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
            $baseEnumName = $enumClass->after('App\\Enums\\');

            $this->handleEnumClone($baseEnumName, $enumClass->toString());
        }

        return self::SUCCESS;
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

        if (!$this->option('force')) {
            $confirmed = confirm(
                label: "Is this correct?",
            );

            if (!$confirmed) {
                error("Aborting conversion");
                return self::FAILURE;
            }
        }

        file_put_contents($enumsTsPath, $newEnumsTsContent);

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
