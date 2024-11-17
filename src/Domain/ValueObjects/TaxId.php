<?php
namespace Qtvhao\OrderModule\Domain\ValueObjects;

use InvalidArgumentException;

class TaxId
{
    private string $taxId;

    public function __construct(string $taxId)
    {
        if (!preg_match('/^\d{10,15}$/', $taxId)) {
            throw new InvalidArgumentException("Invalid Tax ID: {$taxId}");
        }

        $this->taxId = $taxId;
    }

    public function getTaxId(): string
    {
        return $this->taxId;
    }

    public function equals(TaxId $taxId): bool
    {
        return $this->taxId === $taxId->getTaxId();
    }
}