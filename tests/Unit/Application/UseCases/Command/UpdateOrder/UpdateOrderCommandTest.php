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
/* Việc thực hiện validation trong constructor của UpdateOrderCommandRequest có thể khiến nó trở nên quá tải và vi phạm Single Responsibility Principle (SRP).

Dưới đây là cách tiếp cận tốt hơn để xử lý vấn đề này:

1. Xử lý validation ở lớp chuyên biệt

a. Tạo lớp chuyên trách cho việc xác thực: UpdateOrderValidator

Chúng ta tạo một lớp riêng để thực hiện việc kiểm tra tính hợp lệ của dữ liệu đầu vào.

namespace Qtvhao\OrderModule\Application\Validators;

use InvalidArgumentException;

class UpdateOrderValidator
{
    public function validate(string $newStatus): void
    {
        $validStatuses = ['pending', 'shipped', 'delivered', 'canceled'];

        if (!in_array($newStatus, $validStatuses, true)) {
            throw new InvalidArgumentException("Invalid status: {$newStatus}");
        }
    }
}

b. Sửa đổi UpdateOrderCommandRequest

Giữ UpdateOrderCommandRequest đơn giản chỉ làm nhiệm vụ lưu trữ dữ liệu, không thực hiện validation.

namespace Qtvhao\OrderModule\Application\UseCases\Command\UpdateOrder;

class UpdateOrderCommandRequest
{
    private string $orderId;
    private string $newStatus;

    public function __construct(string $orderId, string $newStatus)
    {
        $this->orderId = $orderId;
        $this->newStatus = $newStatus;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getNewStatus(): string
    {
        return $this->newStatus;
    }
}

c. Sửa đổi UpdateOrderHandler để sử dụng validator

Sử dụng lớp UpdateOrderValidator trong UpdateOrderHandler để kiểm tra tính hợp lệ của newStatus.

namespace Qtvhao\OrderModule\Application\UseCases\Command\UpdateOrder;

use Qtvhao\OrderModule\Application\Interfaces\Repositories\OrderCommandRepositoryInterface;
use Qtvhao\OrderModule\Application\Exceptions\OrderNotFoundException;
use Qtvhao\OrderModule\Application\Validators\UpdateOrderValidator;
use Qtvhao\OrderModule\Domain\ValueObjects\OrderStatus;

class UpdateOrderHandler
{
    private OrderCommandRepositoryInterface $repository;
    private UpdateOrderValidator $validator;

    public function __construct(
        OrderCommandRepositoryInterface $repository,
        UpdateOrderValidator $validator
    ) {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function handle(UpdateOrderCommandRequest $command): void
    {
        // Validate input
        $this->validator->validate($command->getNewStatus());

        // Find the order
        $order = $this->repository->findForUpdate($command->getOrderId());

        if (!$order) {
            throw new OrderNotFoundException("Order not found.");
        }

        // Update order status
        $status = OrderStatus::fromString($command->getNewStatus());
        $order->changeStatus($status);

        // Save updated order
        $this->repository->save($order);
    }
}

2. Cập nhật Unit Test

a. Test cập nhật thành công

Test này không cần thay đổi nhiều.

public function testUpdateOrderSuccessfully()
{
    $orderId = 'order-123';
    $newStatus = 'shipped';
    $orderMock = $this->createMock(OrderAggregate::class);
    $repositoryMock = $this->createMock(OrderCommandRepositoryInterface::class);
    $validatorMock = $this->createMock(UpdateOrderValidator::class);

    // Giả lập: Validator không ném ngoại lệ
    $validatorMock->expects($this->once())->method('validate')->with($newStatus);

    // Giả lập: Repository trả về đơn hàng
    $repositoryMock->method('findForUpdate')->with($orderId)->willReturn($orderMock);

    // Giả lập: Gọi changeStatus và save
    $orderMock->expects($this->once())->method('changeStatus')->with($newStatus);
    $repositoryMock->expects($this->once())->method('save')->with($orderMock);

    $handler = new UpdateOrderHandler($repositoryMock, $validatorMock);
    $command = new UpdateOrderCommandRequest($orderId, $newStatus);

    $handler->handle($command);

    $this->assertTrue(true); // Đảm bảo không có ngoại lệ
}

b. Test ném ngoại lệ khi dữ liệu không hợp lệ

public function testUpdateOrderFailsWithInvalidData()
{
    $orderId = 'order-123';
    $invalidStatus = 'invalid-status';
    $repositoryMock = $this->createMock(OrderCommandRepositoryInterface::class);
    $validatorMock = $this->createMock(UpdateOrderValidator::class);

    // Giả lập: Validator ném ngoại lệ
    $validatorMock->method('validate')->with($invalidStatus)->willThrowException(new InvalidArgumentException());

    $handler = new UpdateOrderHandler($repositoryMock, $validatorMock);
    $command = new UpdateOrderCommandRequest($orderId, $invalidStatus);

    $this->expectException(InvalidArgumentException::class);

    $handler->handle($command);
}

c. Test ném ngoại lệ khi không tìm thấy đơn hàng

public function testUpdateOrderFailsWhenOrderNotFound()
{
    $orderId = 'order-404';
    $newStatus = 'shipped';
    $repositoryMock = $this->createMock(OrderCommandRepositoryInterface::class);
    $validatorMock = $this->createMock(UpdateOrderValidator::class);

    // Giả lập: Validator không ném ngoại lệ
    $validatorMock->method('validate')->with($newStatus);

    // Giả lập: Repository trả về null (không tìm thấy đơn hàng)
    $repositoryMock->method('findForUpdate')->with($orderId)->willReturn(null);

    $handler = new UpdateOrderHandler($repositoryMock, $validatorMock);
    $command = new UpdateOrderCommandRequest($orderId, $newStatus);

    $this->expectException(OrderNotFoundException::class);

    $handler->handle($command);
}

3. Kết quả

	•	Ưu điểm của cách làm mới:
	•	Constructor của UpdateOrderCommandRequest chỉ lưu dữ liệu, không thực hiện logic phức tạp.
	•	Validation được tách biệt trong UpdateOrderValidator, dễ dàng kiểm tra và tái sử dụng.
	•	Mã nguồn tuân thủ Single Responsibility Principle.
	•	Kết quả chạy test:
	•	Chạy PHPUnit và đảm bảo toàn bộ test case đều pass:

php artisan test --filter=UpdateOrderCommandTest

Tất cả test case nên pass và hệ thống đã được cấu trúc lại tốt hơn. */
