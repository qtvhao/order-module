<?php
namespace Qtvhao\OrderModule\Tests\Unit\Domain\Events;

use PHPUnit\Framework\TestCase;
use Qtvhao\OrderModule\Domain\Events\OrderCreatedEvent;

class OrderCreatedEventTest extends TestCase
{
    public function testOrderCreatedEventStoresOrderId()
    {
        $orderId = 'ORDER123';
        $event = new OrderCreatedEvent($orderId);

        $this->assertEquals($orderId, $event->getOrderId());
    }

    public function testOrderCreatedEventStoresOccurredOn()
    {
        $event = new OrderCreatedEvent('ORDER123');
        
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->occurredOn());
        $this->assertLessThanOrEqual(new \DateTimeImmutable(), $event->occurredOn());
    }
}
