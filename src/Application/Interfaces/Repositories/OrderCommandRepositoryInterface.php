<?php

namespace Qtvhao\OrderModule\Application\Interfaces\Repositories;

use Qtvhao\OrderModule\Domain\Aggregates\OrderAggregate;

interface OrderCommandRepositoryInterface
{
    /**
     * Lưu một order vào repository.
     *
     * @param OrderAggregate $order
     * @return void
     */
    public function save(OrderAggregate $order): void;

    /**
     * Xóa một order khỏi repository theo ID.
     *
     * @param string $orderId
     * @return void
     */
    public function delete(string $orderId): void;

    /**
     * Tìm một order theo ID để cập nhật.
     *
     * @param string $orderId
     * @return OrderAggregate|null
     */
    public function findForUpdate(string $orderId): ?OrderAggregate;
}
