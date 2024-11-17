<?php
namespace Qtvhao\OrderModule\Domain\ValueObjects;

use InvalidArgumentException;

class Discount
{
    private float $amount;
    private string $type; // 'percentage' or 'fixed'

    public function __construct(float $amount, string $type)
    {
        if (!in_array($type, ['percentage', 'fixed'], true)) {
            throw new InvalidArgumentException("Invalid discount type: {$type}");
        }
        if ($amount < 0 || ($type === 'percentage' && $amount > 100)) {
            throw new InvalidArgumentException("Invalid discount amount: {$amount}");
        }

        $this->amount = $amount;
        $this->type = $type;
    }

    public function applyTo(Money $money): Money
    {
        $discountAmount = $this->type === 'percentage'
            ? $money->getAmount() * ($this->amount / 100)
            : $this->amount;

        return $money->subtract(new Money($discountAmount, $money->getCurrency()));
    }

    public function equals(Discount $discount): bool
    {
        return $this->amount === $discount->amount && $this->type === $discount->type;
    }
}