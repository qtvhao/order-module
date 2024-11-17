<?php
namespace Qtvhao\OrderModule\Domain\Events;

class OrderCreatedEvent extends DomainEvent
{
    private string $orderId;

    public function __construct(string $orderId)
    {
        parent::__construct();
        $this->orderId = $orderId;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }
}
