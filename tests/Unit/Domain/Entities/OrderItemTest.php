<?php

declare(strict_types=1);

namespace Qtvhao\OrderModule\Tests\Unit\Domain\Entities;

use PHPUnit\Framework\TestCase;
use Qtvhao\OrderModule\Domain\Entities\OrderItem;
use Qtvhao\OrderModule\Domain\ValueObjects\Money;
use Qtvhao\OrderModule\Domain\ValueObjects\Quantity;

class OrderItemTest extends TestCase
{
    public function testCreateOrderItem()
    {
        $money = new Money(100, 'USD');
        $quantity = new Quantity(2);
        $orderItem = new OrderItem('SKU123', $money, $quantity);

        $this->assertEquals('SKU123', $orderItem->getSKU());
        $this->assertEquals(100, $orderItem->getPrice()->getAmount());
        $this->assertEquals(2, $orderItem->getQuantity()->getValue());
    }

    public function testCalculateItemTotal()
    {
        $money = new Money(50, 'USD');
        $quantity = new Quantity(3);
        $orderItem = new OrderItem('SKU456', $money, $quantity);

        $this->assertEquals(150, $orderItem->calculateTotal()->getAmount());
    }

    public function testInvalidQuantityThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);

        $money = new Money(100, 'USD');
        $quantity = new Quantity(-1); // Không hợp lệ
        new OrderItem('SKU789', $money, $quantity);
    }
}
