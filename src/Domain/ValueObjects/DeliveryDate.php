<?php
namespace Qtvhao\OrderModule\Domain\ValueObjects;

use DateTime;
use InvalidArgumentException;

class DeliveryDate
{
    private DateTime $date;

    public function __construct(DateTime $date)
    {
        $this->date = $date;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function isBefore(DeliveryDate $otherDate): bool
    {
        return $this->date < $otherDate->getDate();
    }

    public function isAfter(DeliveryDate $otherDate): bool
    {
        return $this->date > $otherDate->getDate();
    }

    public function equals(DeliveryDate $date): bool
    {
        return $this->date == $date->getDate();
    }
}