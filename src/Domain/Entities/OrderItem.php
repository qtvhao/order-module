<?php
namespace Qtvhao\OrderModule\Domain\Entities;

use Qtvhao\OrderModule\Domain\ValueObjects\ProductName;
use Qtvhao\OrderModule\Domain\ValueObjects\SKU;
use Qtvhao\OrderModule\Domain\ValueObjects\Money;
use Qtvhao\OrderModule\Domain\ValueObjects\Quantity;

class OrderItem
{
    private ProductName $name;
    private SKU $sku;
    private Money $price;
    private Quantity $quantity;

    public function __construct(ProductName $name, SKU $sku, Money $price, Quantity $quantity)
    {
        $this->name = $name;
        $this->sku = $sku;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getName(): ProductName
    {
        return $this->name;
    }

    public function getSku(): SKU
    {
        return $this->sku;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function getQuantity(): Quantity
    {
        return $this->quantity;
    }

    public function calculateTotal(): Money
    {
        $totalAmount = $this->price->getAmount() * $this->quantity->getValue();
        return new Money($totalAmount, $this->price->getCurrency());
    }
}
