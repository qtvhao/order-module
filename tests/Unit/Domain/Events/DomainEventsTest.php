<?php
namespace Qtvhao\OrderModule\Tests\Unit\Domain\Events;

use PHPUnit\Framework\TestCase;
use Qtvhao\OrderModule\Domain\Events\OrderCreatedEvent;

class DomainEventsTest extends TestCase
{
    public function testOrderCreatedEvent()
    {
        $event = new OrderCreatedEvent('ORDER123');

        $this->assertEquals('ORDER123', $event->getOrderId());
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->occurredOn());
    }
}
