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
            customerName: new FullName(firstName: 'John', lastName: 'Doe'),
            email: new EmailAddress(email: 'john.doe@example.com'),
            phone: new PhoneNumber(countryCode: '+84', number: '123456789'),
            address: new Address(
                street: '123 Street',
                city: 'Hanoi',
                zipcode: '10000',
                country: 'Vietnam'
            ),
            items: []
        );

        // Assert repository save will be called
        $this->repositoryMock->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(OrderAggregate::class));

        $handler = new CreateOrderHandler(repository: $this->repositoryMock);

        // Act
        $handler->handle(command: $command);

        // Assert: No exceptions thrown, test passes.
        $this->assertTrue(true);
    }

    public function testCreateOrderWithInvalidEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid email address: invalid-email");

        // Arrange
        new CreateOrderCommandRequest(
            customerName: new FullName(firstName: 'John', lastName: 'Doe'),
            email: new EmailAddress(email: 'invalid-email'),
            phone: new PhoneNumber(countryCode: '+84', number: '123456789'),
            address: new Address(
                street: '123 Street',
                city: 'Hanoi',
                zipcode: '10000',
                country: 'Vietnam'
            ),
            items: []
        );

        // Act: Exception should be thrown.
    }

    public function testCreateOrderWithInvalidPhoneNumber(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid phone number: 123");

        // Arrange
        new CreateOrderCommandRequest(
            customerName: new FullName(firstName: 'John', lastName: 'Doe'),
            email: new EmailAddress(email: 'john.doe@example.com'),
            phone: new PhoneNumber(countryCode: '+84', number: '123'),
            address: new Address(
                street: '123 Street',
                city: 'Hanoi',
                zipcode: '10000',
                country: 'Vietnam'
            ),
            items: []
        );

        // Act: Exception should be thrown.
    }
}
