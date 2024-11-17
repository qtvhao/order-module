<?php
namespace Qtvhao\OrderModule\Domain\ValueObjects;

use InvalidArgumentException;

class SKU
{
    private string $sku;

    public function __construct(string $sku)
    {
        if (!preg_match('/^[A-Z0-9\-]{5,20}$/', $sku)) {
            throw new InvalidArgumentException("Invalid SKU: {$sku}");
        }
        $this->sku = $sku;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function equals(SKU $sku): bool
    {
        return $this->sku === $sku->getSku();
    }
}