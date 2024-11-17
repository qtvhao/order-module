<?php
namespace Qtvhao\OrderModule\Tests\Unit\Domain\Aggregates;

use PHPUnit\Framework\TestCase;
use Qtvhao\OrderModule\Domain\Aggregates\OrderAggregate;
use Qtvhao\OrderModule\Domain\Entities\OrderItem;
use Qtvhao\OrderModule\Domain\ValueObjects\OrderStatus;
use Qtvhao\OrderModule\Domain\ValueObjects\ProductName;
use Qtvhao\OrderModule\Domain\ValueObjects\SKU;
use Qtvhao\OrderModule\Domain\ValueObjects\Money;
use Qtvhao\OrderModule\Domain\ValueObjects\Quantity;

class OrderAggregateTest extends TestCase
{
    public function testCreateOrder()
    {
        $order = new OrderAggregate();

        $this->assertEquals('pending', $order->getStatus()->getStatus());
        $this->assertEmpty($order->getItems());
    }

    public function testAddItemToOrder()
    {
        $order = new OrderAggregate();
        $item = new OrderItem(
            new ProductName('Laptop'),
            new SKU('LAP-12345'),
            new Money(1000.00, 'USD'),
            new Quantity(1)
        );

        $order->addItem($item);

        $this->assertCount(1, $order->getItems());
        $this->assertEquals($item, $order->getItems()[0]);
    }

    public function testChangeOrderStatus()
    {
        $order = new OrderAggregate();
        $order->changeStatus(OrderStatus::shipped());

        $this->assertEquals('shipped', $order->getStatus()->getStatus());
    }

    public function testCalculateOrderTotal()
    {
        $order = new OrderAggregate();
        $item1 = new OrderItem(
            new ProductName('Laptop'),
            new SKU('LAP-12345'),
            new Money(1000.00, 'USD'),
            new Quantity(2)
        );
        $item2 = new OrderItem(
            new ProductName('Mouse'),
            new SKU('MOU-54321'),
            new Money(50.00, 'USD'),
            new Quantity(3)
        );

        $order->addItem($item1);
        $order->addItem($item2);

        $total = $order->calculateTotal();

        $this->assertEquals(2150.00, $total->getAmount());
        $this->assertEquals('USD', $total->getCurrency());
    }

    public function testCalculateOrderTotalWithEmptyItems()
    {
        $order = new OrderAggregate();
        $total = $order->calculateTotal();

        $this->assertEquals(0, $total->getAmount());
    }
}
