<?php

namespace Qtvhao\OrderModule\Domain\ValueObjects;

use InvalidArgumentException;

class Money
{
    private float $amount;
    private string $currency;

    public function __construct(float $amount, string $currency)
    {
        if ($amount < 0) {
            throw new InvalidArgumentException("Amount must be non-negative.");
        }

        if (!preg_match('/^[A-Z]{3}$/', $currency)) {
            throw new InvalidArgumentException("Currency must be a valid ISO 4217 code.");
        }

        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function equals(Money $money): bool
    {
        return $this->amount === $money->amount &&
               $this->currency === $money->currency;
    }

    public function add(Money $money): Money
    {
        if ($this->currency !== $money->currency) {
            throw new InvalidArgumentException("Currencies must match to add amounts.");
        }

        return new Money($this->amount + $money->amount, $this->currency);
    }

    public function subtract(Money $money): Money
    {
        if ($this->currency !== $money->currency) {
            throw new InvalidArgumentException("Currencies must match to subtract amounts.");
        }

        $newAmount = $this->amount - $money->amount;
        if ($newAmount < 0) {
            throw new InvalidArgumentException("Resulting amount must be non-negative.");
        }

        return new Money($newAmount, $this->currency);
    }
}