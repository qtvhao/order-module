<?php
namespace Qtvhao\OrderModule\Domain\Services;

use Qtvhao\OrderModule\Domain\Entities\OrderItem;
use Qtvhao\OrderModule\Domain\ValueObjects\Money;
use Qtvhao\OrderModule\Domain\ValueObjects\Discount;

class OrderCalculationService
{
    public function calculateTotal(array $items, ?Discount $discount = null): Money
    {
        $totalAmount = 0;
        $currency = null;

        foreach ($items as $item) {
            if (!$item instanceof OrderItem) {
                throw new \InvalidArgumentException('Invalid order item.');
            }
            $totalAmount += $item->calculateTotal()->getAmount();
            $currency = $item->getPrice()->getCurrency();
        }

        $totalMoney = new Money($totalAmount, $currency);

        return $discount ? $discount->applyTo($totalMoney) : $totalMoney;
    }
}
