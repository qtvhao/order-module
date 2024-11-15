## Tóm tắt quá trình TDD cho module này

### 1. Domain Layer

#### Value Objects

1. Viết bài test cho từng Value Object:
    - Tạo các bài test cho `Address.php`, `Money.php`, và `OrderStatus.php`.
    - Test các thuộc tính, phương thức bất biến, và các điều kiện hợp lệ.
    - Ví dụ: Đối với `Money.php`, kiểm tra tính hợp lệ của các giá trị số âm và phép cộng, trừ giữa các đối tượng Money.
2. Xây dựng Value Objects:
    - Sau khi các bài test đã được viết, triển khai từng lớp Value Object để vượt qua các bài test đó.

#### Entities và Aggregates

1. Viết bài test cho Entities và Aggregates:
    - Tạo các bài test cho `OrderItem.php` và `OrderAggregate.php`.
    - Kiểm tra việc tạo, thêm item, thay đổi trạng thái order, tính toán tổng, v.v.
2. Xây dựng Entities và Aggregates:
    - Xây dựng `OrderItem.php` và `OrderAggregate.php` cho đến khi các bài test thành công.

#### Services và Events

1. Viết test cho Services và Events:
    - Kiểm tra các phương thức của `OrderCalculationService.php` và các domain events như `OrderCreatedEvent.php`, `OrderItemAddedEvent.php`.
2. Xây dựng Services và Events:
    - Thực hiện các lớp và hàm cần thiết cho đến khi tất cả các test pass.

#### Exceptions

1. Viết test cho các Exception:
    - Tạo bài test để đảm bảo `OrderDomainException.php` hoạt động đúng khi có lỗi nghiệp vụ.
2. Xây dựng Exceptions:
    - Xây dựng các exception cần thiết.

### 2. Application Layer

#### Interfaces và Use Cases

1. Viết bài test cho Interfaces và Use Cases:
    - Mô phỏng hành vi của các interface `OrderCommandRepositoryInterface.php`, `OrderQueryRepositoryInterface.php`, `EventBusInterface.php`.
    - Viết các bài test cho các command (`CreateOrderCommand`, `UpdateOrderCommand`) và query (`GetOrder`, `ListOrders`).
2. Xây dựng Interfaces và Use Cases:
    - Xây dựng các interface và use cases để pass các bài test.

#### Services và Event Handlers

1. Viết test cho Application Services và Event Handlers:
    - Viết test cho `CalculateOrderTotalService.php`, `ReadModelBuilder.php`, và `OrderEventHandler.php`.
2. Xây dựng Services và Event Handlers:
    - Xây dựng và chỉnh sửa cho đến khi các test pass.

#### Exceptions

1. Viết test cho Application Exceptions:
    - Tạo test cho `OrderNotFoundException.php`.
2. Xây dựng Exceptions:
    - Hoàn thiện exception này.

### 3. Infrastructure Layer

#### Persistence

1. Viết bài test cho Persistence:
    - Viết các bài test cho `EventSourcedOrderCommandRepository.php` và `MySqlOrderQueryRepository.php`.
    - Sử dụng các fake hoặc in-memory database để test.
2. Xây dựng Persistence Repositories:
    - Cài đặt và tinh chỉnh cho đến khi các test pass.

#### Events

1. Viết test cho Event Bus và Producers/Consumers:
    - Tạo bài test cho `KafkaEventBus.php` và các producer/consumer Kafka.
2. Xây dựng Events và Messaging:
    - Cài đặt event bus và các lớp producer/consumer để pass test.

#### Controllers và Workers

1. Viết test cho Controllers và Workers:
    - Viết test cho `OrderController.php`, `OrderApiController.php`, và `KafkaConsumerWorker.php`.
2. Xây dựng Controllers và Workers:
    - Cài đặt các controller và worker để pass test.

#### Policies và Providers

1. Viết test cho Policies và Providers:
    - Tạo test cho `OrderPolicy.php`, `RepositoryServiceProvider.php`, và `EventBusServiceProvider.php`.
2. Xây dựng Policies và Providers:
    - Hoàn thiện policies và providers cho đến khi tất cả test pass.

### 4. Presentation Layer

#### Requests và Resources

1. Viết test cho Requests và Resources:
    - Viết test cho `CreateOrderRequest.php`, `UpdateOrderRequest.php`, và `OrderResource.php`.
2. Xây dựng Requests và Resources:
    - Triển khai lớp và định dạng response cho đến khi test pass.

#### Routes

1. Viết test cho Routes:
    - Viết bài test cho API routes trong `api.php`.
2. Cài đặt Routes:
    - Định nghĩa các endpoint trong file routes cho đến khi test pass.

### 5. Testing

Khi toàn bộ code đã được xây dựng bằng TDD, thực hiện các bài Integration Test và End-to-End Test:
1. Integration Tests: Test tích hợp giữa các lớp để kiểm tra sự tương tác giữa domain, application, và infrastructure.
2. End-to-End Tests: Kiểm tra luồng dữ liệu từ API, qua business logic, đến database để đảm bảo hệ thống hoạt động như mong đợi.
