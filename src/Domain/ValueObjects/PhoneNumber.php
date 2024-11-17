<?php
namespace Qtvhao\OrderModule\Domain\ValueObjects;

use InvalidArgumentException;

class PhoneNumber
{
    private string $countryCode;
    private string $number;

    public function __construct(string $countryCode, string $number)
    {
        if (!preg_match('/^\+\d{1,3}$/', $countryCode)) {
            throw new InvalidArgumentException("Invalid country code: {$countryCode}");
        }
        if (!preg_match('/^\d{7,15}$/', $number)) {
            throw new InvalidArgumentException("Invalid phone number: {$number}");
        }

        $this->countryCode = $countryCode;
        $this->number = $number;
    }

    public function getFullNumber(): string
    {
        return "{$this->countryCode} {$this->number}";
    }

    public function equals(PhoneNumber $phone): bool
    {
        return $this->getFullNumber() === $phone->getFullNumber();
    }
}