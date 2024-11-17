<?php

namespace Qtvhao\OrderModule\Domain\ValueObjects;

use InvalidArgumentException;

class ProductName
{
    private string $name;

    public function __construct(string $name)
    {
        $this->validate($name);
        $this->name = $name;
    }

    private function validate(string $name): void
    {
        if (empty(trim($name))) {
            throw new InvalidArgumentException("Product name cannot be empty.");
        }

        if (strlen($name) > 255) {
            throw new InvalidArgumentException("Product name cannot exceed 255 characters.");
        }

        if (!preg_match('/^[a-zA-Z0-9\s\-]+$/', $name)) {
            throw new InvalidArgumentException("Product name contains invalid characters.");
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function equals(ProductName $other): bool
    {
        return $this->name === $other->getName();
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
