<?php

namespace Qtvhao\OrderModule\Domain\ValueObjects;

use InvalidArgumentException;

class OrderStatus
{
    private const VALID_STATUSES = [
        'pending',
        'shipped',
        'delivered',
        'cancelled',
    ];

    private string $status;

    public function __construct(string $status)
    {
        if (!in_array($status, self::VALID_STATUSES, true)) {
            throw new InvalidArgumentException("Invalid order status: {$status}");
        }

        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function equals(OrderStatus $status): bool
    {
        return $this->status === $status->status;
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isShipped(): bool
    {
        return $this->status === 'shipped';
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public static function pending(): OrderStatus
    {
        return new self('pending');
    }

    public static function shipped(): OrderStatus
    {
        return new self('shipped');
    }

    public static function delivered(): OrderStatus
    {
        return new self('delivered');
    }

    public static function cancelled(): OrderStatus
    {
        return new self('cancelled');
    }
}