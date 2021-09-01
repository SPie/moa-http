<?php

namespace Moa\Http;

final class Header
{
    public function __construct(private string $name, private string $normalizedName, private array $values)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNormalizedName(): string
    {
        return $this->normalizedName;
    }

    public function setValues(array $values): self
    {
        $this->values = $values;

        return $this;
    }

    public function addValue(string $value): self
    {
        $this->values[] = $value;

        return $this;
    }

    public function getValues(): array
    {
        return $this->values;
    }
}
