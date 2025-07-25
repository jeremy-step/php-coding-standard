<?php

namespace PHPStylish\Input;

final class EnvInput  extends BaseInput implements NamedInput
{
    private readonly string $name;

    public function __construct(
        string $name,
        private readonly string $value,
    )
    {
        $this->sanitizeName($name);
    }

    private function sanitizeName(string $name): void
    {
        $this->name = trim($name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function escape(): self
    {
        if ($this->isEscaped()) {
            return $this;
        }

        return new self(
            escapeshellcmd($this->getName()), 
            escapeshellarg($this->getValue()),
        )->setEscaped();
    }

    public function __toString(): string
    {
        $input = $this->escape();
        
        return "{$input->getName()}={$input->getValue()}";
    }
}
