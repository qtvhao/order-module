<?php

declare(strict_types=1);

namespace Qtvhao\OrderModule\Tests\Unit\Domain\Entities;

use PHPUnit\Framework\TestCase;
use Qtvhao\OrderModule\Domain\Entities\OrderItem;
use Qtvhao\OrderModule\Domain\ValueObjects\ProductName;
use Qtvhao\OrderModule\Domain\ValueObjects\SKU;
use Qtvhao\OrderModule\Domain\ValueObjects\Money;
use Qtvhao\OrderModule\Domain\ValueObjects\Quantity;

class OrderItemTest extends TestCase
{
    public function testCreateOrderItem()
    {
        $name = new ProductName('Laptop');
        $sku = new SKU('LAP-12345');
        $price = new Money(1000.00, 'USD');
        $quantity = new Quantity(2);

        $orderItem = new OrderItem($name, $sku, $price, $quantity);

        $this->assertEquals($name, $orderItem->getName());
        $this->assertEquals($sku, $orderItem->getSku());
        $this->assertEquals($price, $orderItem->getPrice());
        $this->assertEquals($quantity, $orderItem->getQuantity());
    }

    public function testCalculateTotal()
    {
        $price = new Money(1000.00, 'USD');
        $quantity = new Quantity(3);
        $orderItem = new OrderItem(
            new ProductName('Laptop'),
            new SKU('LAP-12345'),
            $price,
            $quantity
        );

        $total = $orderItem->calculateTotal();

        $this->assertEquals(3000.00, $total->getAmount());
        $this->assertEquals('USD', $total->getCurrency());
    }
}
