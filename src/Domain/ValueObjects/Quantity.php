<?php
namespace Qtvhao\OrderModule\Domain\ValueObjects;

use InvalidArgumentException;

class Quantity
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new InvalidArgumentException("Quantity must be non-negative.");
        }
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function add(Quantity $quantity): Quantity
    {
        return new Quantity($this->value + $quantity->getValue());
    }

    public function subtract(Quantity $quantity): Quantity
    {
        $newValue = $this->value - $quantity->getValue();
        if ($newValue < 0) {
            throw new InvalidArgumentException("Resulting quantity must be non-negative.");
        }
        return new Quantity($newValue);
    }

    public function equals(Quantity $quantity): bool
    {
        return $this->value === $quantity->getValue();
    }
}