<?php

namespace Tests\Unit\Application\UseCases\Command\UpdateOrder;

use PHPUnit\Framework\TestCase;
use Qtvhao\OrderModule\Application\UseCases\Command\UpdateOrder\UpdateOrderHandler;
use Qtvhao\OrderModule\Application\UseCases\Command\UpdateOrder\UpdateOrderCommandRequest;
use Qtvhao\OrderModule\Application\Interfaces\Repositories\OrderCommandRepositoryInterface;
use Qtvhao\OrderModule\Domain\Aggregates\OrderAggregate;
use Qtvhao\OrderModule\Domain\ValueObjects\FullName;
use Qtvhao\OrderModule\Domain\ValueObjects\EmailAddress;
use Qtvhao\OrderModule\Domain\ValueObjects\Address;
use Qtvhao\OrderModule\Domain\ValueObjects\PhoneNumber;
use Qtvhao\OrderModule\Application\Exceptions\OrderNotFoundException;
use InvalidArgumentException;

class UpdateOrderCommandTest extends TestCase
{
    private OrderCommandRepositoryInterface $repository;
    private UpdateOrderHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(OrderCommandRepositoryInterface::class);
        $this->handler = new UpdateOrderHandler($this->repository);
    }

    /** @test */
    public function test_it_should_update_order_successfully(): void
    {
        // Arrange
        $orderId = 'order-123';
        $updatedName = new FullName('John', 'Doe');
        $updatedEmail = new EmailAddress('john.doe@example.com');
        $updatedAddress = new Address('123 Main St', 'New York', '10001', 'USA');
        $updatedPhone = new PhoneNumber('+1', '1234567890');
        $order = $this->createMock(OrderAggregate::class);

        // Mock repository behavior
        $this->repository->method('findForUpdate')->with($orderId)->willReturn($order);

        // Expect method calls on the order object
        $order->expects($this->once())->method('updateDetails')
            ->with($updatedName, $updatedEmail, $updatedAddress, $updatedPhone);

        $command = new UpdateOrderCommandRequest($orderId, $updatedName, $updatedEmail, $updatedAddress, $updatedPhone);

        // Act
        $this->handler->handle($command);

        // Assert
        $this->assertTrue(true); // Ensure no exceptions were thrown
    }

    /** @test */
    public function test_it_should_throw_exception_if_order_not_found(): void
    {
        // Arrange
        $orderId = 'non-existent-order';
        $updatedName = new FullName('Jane', 'Doe');
        $updatedEmail = new EmailAddress('jane.doe@example.com');
        $updatedAddress = new Address('456 Elm St', 'Los Angeles', '90001', 'USA');
        $updatedPhone = new PhoneNumber('+1', '9876543210');

        $this->repository->method('findForUpdate')->with($orderId)->willReturn(null);

        $command = new UpdateOrderCommandRequest($orderId, $updatedName, $updatedEmail, $updatedAddress, $updatedPhone);

        // Assert exception
        $this->expectException(OrderNotFoundException::class);
        $this->expectExceptionMessage("Order with ID {$orderId} not found.");

        // Act
        $this->handler->handle($command);
    }

    /** @test */
    public function test_it_should_throw_exception_if_update_order_with_invalid_data(): void
    {
        // Arrange
        $this->expectException(InvalidArgumentException::class);

        $orderId = 'order-123';
        $updatedName = new FullName('', 'Doe'); // Invalid name
        $updatedEmail = new EmailAddress('invalid-email'); // Invalid email
        $updatedAddress = new Address('123 Main St', 'New York', '10001', 'USA');
        $updatedPhone = new PhoneNumber('+1', '1234567890');

        $command = new UpdateOrderCommandRequest($orderId, $updatedName, $updatedEmail, $updatedAddress, $updatedPhone);

        // Act
        $this->handler->handle($command);
    }
}
