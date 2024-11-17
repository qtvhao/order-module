<?php
namespace Qtvhao\OrderModule\Domain\Events;

use Qtvhao\OrderModule\Domain\Entities\OrderItem;

class OrderItemAddedEvent extends DomainEvent
{
    private OrderItem $orderItem;

    public function __construct(OrderItem $orderItem)
    {
        parent::__construct();
        $this->orderItem = $orderItem;
    }

    public function getOrderItem(): OrderItem
    {
        return $this->orderItem;
    }
}
