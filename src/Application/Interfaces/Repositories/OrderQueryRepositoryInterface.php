<?php

namespace Qtvhao\OrderModule\Application\Interfaces\Repositories;

use Qtvhao\OrderModule\Domain\Aggregates\OrderAggregate;

interface OrderQueryRepositoryInterface
{
    /**
     * Lấy một order theo ID.
     *
     * @param string $orderId
     * @return OrderAggregate|null
     */
    public function findById(string $orderId): ?OrderAggregate;

    /**
     * Lấy danh sách tất cả các orders.
     *
     * @return array
     */
    public function findAll(): array;

    /**
     * Tìm kiếm các orders theo trạng thái.
     *
     * @param string $status
     * @return array
     */
    public function findByStatus(string $status): array;

    /**
     * Kiểm tra xem một order có tồn tại hay không.
     *
     * @param string $orderId
     * @return bool
     */
    public function exists(string $orderId): bool;
}
