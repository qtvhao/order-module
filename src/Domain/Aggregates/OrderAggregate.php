<?php
namespace Qtvhao\OrderModule\Domain\Aggregates;

use Qtvhao\OrderModule\Domain\Entities\OrderItem;
use Qtvhao\OrderModule\Domain\ValueObjects\OrderStatus;
use Qtvhao\OrderModule\Domain\ValueObjects\Money;

class OrderAggregate
{
    private array $items = [];
    private OrderStatus $status;

    public function __construct()
    {
        $this->status = OrderStatus::pending();
    }

    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    public function changeStatus(OrderStatus $newStatus): void
    {
        $this->status = $newStatus;
    }

    public function addItem(OrderItem $item): void
    {
        $this->items[] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function calculateTotal(): Money
    {
        $totalAmount = 0;
        $currency = null;

        foreach ($this->items as $item) {
            $totalAmount += $item->calculateTotal()->getAmount();
            $currency = $item->getPrice()->getCurrency(); // Assuming all items have the same currency
        }

        return new Money($totalAmount, $currency);
    }
}
