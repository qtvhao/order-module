<?php

namespace Qtvhao\OrderModule\Tests\Unit\Domain\ValueObjects;

use Qtvhao\OrderModule\Domain\ValueObjects\Money;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Error;

class MoneyTest extends TestCase
{
    public function testCanCreateMoney()
    {
        $money = new Money(100.50, "USD");

        $this->assertEquals(100.50, $money->getAmount());
        $this->assertEquals("USD", $money->getCurrency());
    }

    public function testMoneyEquality()
    {
        $money1 = new Money(100.50, "USD");
        $money2 = new Money(100.50, "USD");
        $money3 = new Money(200.00, "USD");

        $this->assertTrue($money1->equals($money2));
        $this->assertFalse($money1->equals($money3));
    }

    public function testImmutability()
    {
        $money = new Money(100.50, "USD");

        $this->expectException(\Error::class);
        $money->amount = 200.00;
    }

    public function testInvalidAmount()
    {
        $this->expectException(InvalidArgumentException::class);
        new Money(-100.50, "USD");
    }

    public function testInvalidCurrency()
    {
        $this->expectException(InvalidArgumentException::class);
        new Money(100.50, "INVALID");
    }
}
