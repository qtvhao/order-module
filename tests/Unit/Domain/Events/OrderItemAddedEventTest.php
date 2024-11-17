<?php
namespace Qtvhao\OrderModule\Tests\Unit\Domain\Events;

use PHPUnit\Framework\TestCase;
use Qtvhao\OrderModule\Domain\Events\OrderItemAddedEvent;
use Qtvhao\OrderModule\Domain\Entities\OrderItem;
use Qtvhao\OrderModule\Domain\ValueObjects\ProductName;
use Qtvhao\OrderModule\Domain\ValueObjects\SKU;
use Qtvhao\OrderModule\Domain\ValueObjects\Money;
use Qtvhao\OrderModule\Domain\ValueObjects\Quantity;

class OrderItemAddedEventTest extends TestCase
{
    public function testOrderItemAddedEventStoresOrderItem()
    {
        $item = new OrderItem(
            new ProductName('Product 1'),
            new SKU('SKU12345'),
            new Money(100, 'USD'),
            new Quantity(2)
        );

        $event = new OrderItemAddedEvent($item);

        $this->assertSame($item, $event->getOrderItem());
    }

    public function testOrderItemAddedEventStoresOccurredOn()
    {
        $item = new OrderItem(
            new ProductName('Product 1'),
            new SKU('SKU12345'),
            new Money(100, 'USD'),
            new Quantity(2)
        );

        $event = new OrderItemAddedEvent($item);

        $this->assertInstanceOf(\DateTimeImmutable::class, $event->occurredOn());
        $this->assertLessThanOrEqual(new \DateTimeImmutable(), $event->occurredOn());
    }
}
