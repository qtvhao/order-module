<?php
namespace Qtvhao\OrderModule\Domain\ValueObjects;

use InvalidArgumentException;

class FullName
{
    private string $firstName;
    private string $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        if (empty(trim($firstName)) || empty(trim($lastName))) {
            throw new InvalidArgumentException("First name and last name cannot be empty.");
        }

        $this->firstName = trim($firstName);
        $this->lastName = trim($lastName);
    }

    public function getFullName(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }

    public function equals(FullName $name): bool
    {
        return $this->getFullName() === $name->getFullName();
    }
}