<?php
namespace Qtvhao\OrderModule\Tests\Unit\Domain\Services;


use PHPUnit\Framework\TestCase;
use Qtvhao\OrderModule\Domain\Services\OrderCalculationService;
use Qtvhao\OrderModule\Domain\Entities\OrderItem;
use Qtvhao\OrderModule\Domain\ValueObjects\Money;
use Qtvhao\OrderModule\Domain\ValueObjects\Quantity;
use Qtvhao\OrderModule\Domain\ValueObjects\ProductName;
use Qtvhao\OrderModule\Domain\ValueObjects\SKU;
use Qtvhao\OrderModule\Domain\ValueObjects\Discount;

class OrderCalculationServiceTest extends TestCase
{
    public function testCalculateTotalWithoutDiscount()
    {
        $item1 = new OrderItem(
            new ProductName('Product 1'),
            new SKU('SKU12345'),
            new Money(100, 'USD'),
            new Quantity(2)
        );
        $item2 = new OrderItem(
            new ProductName('Product 2'),
            new SKU('SKU67890'),
            new Money(50, 'USD'),
            new Quantity(3)
        );

        $service = new OrderCalculationService();
        $total = $service->calculateTotal([$item1, $item2]);

        $this->assertEquals(350, $total->getAmount());
        $this->assertEquals('USD', $total->getCurrency());
    }

    public function testCalculateTotalWithDiscount()
    {
        $item = new OrderItem(
            new ProductName('Product 1'),
            new SKU('SKU12345'),
            new Money(200, 'USD'),
            new Quantity(1)
        );

        $discount = new Discount(10, 'percentage');

        $service = new OrderCalculationService();
        $total = $service->calculateTotal([$item], $discount);

        $this->assertEquals(180, $total->getAmount());
        $this->assertEquals('USD', $total->getCurrency());
    }
}
