<?php

namespace Qtvhao\OrderModule\Tests\Unit\Domain\ValueObjects;

use Qtvhao\OrderModule\Domain\ValueObjects\OrderStatus;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Error;

class OrderStatusTest extends TestCase
{
    public function testCanCreateOrderStatus()
    {
        $status = new OrderStatus("pending");

        $this->assertEquals("pending", $status->getStatus());
    }

    public function testOrderStatusEquality()
    {
        $status1 = new OrderStatus("shipped");
        $status2 = new OrderStatus("shipped");
        $status3 = new OrderStatus("delivered");

        $this->assertTrue($status1->equals($status2));
        $this->assertFalse($status1->equals($status3));
    }

    public function testInvalidOrderStatus()
    {
        $this->expectException(InvalidArgumentException::class);
        new OrderStatus("invalid_status");
    }

    public function testImmutability()
    {
        $status = new OrderStatus("cancelled");

        $this->expectException(\Error::class);
        $status->status = "pending";
    }
}