<?php

namespace Tests\Application\UseCases\Command\CreateOrder;

use PHPUnit\Framework\TestCase;
use Qtvhao\OrderModule\Application\UseCases\Command\CreateOrder\CreateOrderHandler;
use Qtvhao\OrderModule\Application\UseCases\Command\CreateOrder\CreateOrderCommandRequest;
use Qtvhao\OrderModule\Domain\ValueObjects\FullName;
use Qtvhao\OrderModule\Domain\ValueObjects\EmailAddress;
use Qtvhao\OrderModule\Domain\ValueObjects\PhoneNumber;
use Qtvhao\OrderModule\Domain\ValueObjects\Address;
use Qtvhao\OrderModule\Domain\Aggregates\OrderAggregate;
use Qtvhao\OrderModule\Application\Interfaces\Repositories\OrderCommandRepositoryInterface;
use InvalidArgumentException;

class CreateOrderCommandTest extends TestCase
{
    private $repositoryMock;

    protected function setUp(): void
    {
        // Mock OrderCommandRepositoryInterface
        $this->repositoryMock = $this->createMock(OrderCommandRepositoryInterface::class);
    }

    public function testCreateOrderSuccessfully(): void
    {
        // Arrange
        $command = new CreateOrderCommandRequest(
            new FullName('John', 'Doe'),
            new EmailAddress('john.doe@example.com'),
            new PhoneNumber('+84', '123456789'),
            new Address('123 Street', 'Hanoi', '10000', 'Vietnam'),
            [] // Empty order items
        );

        // Assert repository save will be called
        $this->repositoryMock->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(OrderAggregate::class));

        $handler = new CreateOrderHandler($this->repositoryMock);

        // Act
        $handler->handle($command);

        // Assert: No exceptions thrown, test passes.
        $this->assertTrue(true);
    }

    public function testCreateOrderWithInvalidEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid email address: invalid-email");

        // Arrange
        new CreateOrderCommandRequest(
            new FullName('John', 'Doe'),
            new EmailAddress('invalid-email'),
            new PhoneNumber('+84', '123456789'),
            new Address('123 Street', 'Hanoi', '10000', 'Vietnam'),
            [] // Empty order items
        );

        // Act: Exception should be thrown.
    }

    public function testCreateOrderWithInvalidPhoneNumber(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid phone number: 123");

        // Arrange
        new CreateOrderCommandRequest(
            new FullName('John', 'Doe'),
            new EmailAddress('john.doe@example.com'),
            new PhoneNumber('+84', '123'),
            new Address('123 Street', 'Hanoi', '10000', 'Vietnam'),
            []
        );

        // Act: Exception should be thrown.
    }
}
