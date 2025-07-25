<?php

declare(strict_types=1);

namespace PHPStylish\Config;

enum Presets: string
{
    private const string Separator = '-';

    case Default = 'Default configuration with an empty ruleset';
    case Stylish = 'A mix of the Nette and Laravel Pint coding standards, with some additional rules';
    case Nette = 'Nette Coding Standard';
    case Laravel = 'Laravel Pint Coding Standard (Laravel preset)';

    public function getName(): string
    {
        return mb_strtolower(preg_replace('/(?<!^)(?=[A-Z])/', self::Separator, $this->name));
    }

    public function getDescription(): string
    {
        return $this->value;
    }

    /**
     * @return ($consoleOutput is true ? string : array)
     */
    public static function getDescriptions(bool $consoleOutput = false): array|string
    {
        if ($consoleOutput === false) {
            return array_map(fn (self $preset): string => "{$preset->getName()}: {$preset->getDescription()}", self::cases());
        }

        return implode("\n", array_map(
            fn(self $preset): string => "\e[b;green]{$preset->getName()}:\e[reset] {$preset->getDescription()}\e[reset]",
            self::cases()
        ));
    }

    public static function isValid(string $preset): bool
    {
        return self::tryFromName($preset) !== null;
    }

    public static function tryFromName(string $name): ?self
    {
        $name = explode(self::Separator, $name);
        $name = array_map(fn(string $name): string => mb_ucfirst($name), $name);
        $name = implode('', $name);
        
        $cases = array_filter(self::cases(), fn(self $case): bool => $case->name === $name);
        $case = reset($cases);

        return $case === false ? null : $case;
    }
}