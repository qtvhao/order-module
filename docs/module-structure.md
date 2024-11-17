# Module Structure: Order

## Overview
The `Order` module is responsible for managing order lifecycle, including creation, updates, cancellations, and querying order data. It uses a **Domain-Driven Design (DDD)** approach, integrates **CQRS** for separating read/write concerns, and adopts an **Event-Driven Architecture** using Kafka.

---
```
Order/
├── src/
│   ├── Domain/
│   │   ├── Aggregates/                        # Aggregate Roots
│   │   │   └── OrderAggregate.php
│   │   ├── Entities/                          # Entities within Aggregates
│   │   │   └── OrderItem.php
│   │   ├── ValueObjects/                      # Immutable value objects
│   │   │   ├── Address.php
│   │   │   ├── DeliveryDate.php
│   │   │   ├── Discount.php
│   │   │   ├── EmailAddress.php
│   │   │   ├── FullName.php
│   │   │   ├── IPAddress.php
│   │   │   ├── Money.php
│   │   │   ├── OrderStatus.php
│   │   │   ├── PhoneNumber.php
│   │   │   ├── Quantity.php
│   │   │   ├── SKU.php
│   │   │   └── TaxId.php
│   │   ├── Services/                          # Pure domain services
│   │   │   └── OrderCalculationService.php
│   │   ├── Events/                            # Domain events and Event Store
│   │   │   ├── DomainEvent.php                # Base class for all events
│   │   │   ├── OrderCreatedEvent.php
│   │   │   ├── OrderItemAddedEvent.php
│   │   │   └── EventStore.php                 # Event Store for saving and publishing events
│   │   └── Exceptions/                        # Domain-specific exceptions
│   │       └── OrderDomainException.php
│   │
│   ├── Application/
│   │   ├── UseCases/                          # Specific application use cases
│   │   │   ├── Command/
│   │   │   │   ├── CreateOrder/
│   │   │   │   │   ├── CreateOrderHandler.php
│   │   │   │   │   └── CreateOrderCommandRequest.php
│   │   │   │   ├── UpdateOrder/
│   │   │   │   │   ├── UpdateOrderHandler.php
│   │   │   │   │   └── UpdateOrderCommandRequest.php
│   │   │   │   └── CancelOrder/
│   │   │   │       ├── CancelOrderHandler.php
│   │   │   │       └── CancelOrderCommandRequest.php
│   │   │   └── Query/
│   │   │       ├── GetOrder/
│   │   │       │   ├── GetOrderHandler.php
│   │   │       │   └── GetOrderRequest.php
│   │   │       └── ListOrders/
│   │   │           ├── ListOrdersHandler.php
│   │   │           └── ListOrdersRequest.php
│   │   ├── Services/                          # Application-specific services
│   │   │   ├── CalculateOrderTotalService.php # Combines domain services with repositories
│   │   │   └── ReadModelBuilder.php           # Updates Read Models from Events
│   │   ├── Interfaces/                        # Interfaces for dependencies
│   │   │   ├── Repositories/
│   │   │   │   ├── OrderCommandRepositoryInterface.php
│   │   │   │   └── OrderQueryRepositoryInterface.php
│   │   │   └── EventBusInterface.php          # Abstraction for event messaging
│   │   ├── EventHandlers/                     # Handlers for Domain Events
│   │   │   └── OrderEventHandler.php
│   │   └── Exceptions/                        # Application-specific exceptions
│   │       └── OrderNotFoundException.php
│   │
│   ├── Infrastructure/
│   │   ├── Persistence/                       # Data persistence for Command and Query
│   │   │   ├── Command/
│   │   │   │   └── EventSourcedOrderCommandRepository.php
│   │   │   ├── Query/
│   │   │   │   └── MySqlOrderQueryRepository.php
│   │   │   └── ReadModels/                    # SQL or schema for Read Models
│   │   │       └── order_read_models.sql
│   │   ├── Events/                            # Event Bus and messaging implementation
│   │   │   ├── KafkaEventBus.php              # Kafka implementation of EventBusInterface
│   │   │   ├── Producers/                     # Kafka producers for Command Side
│   │   │   │   ├── PublishOrderCreated.php
│   │   │   │   ├── PublishOrderUpdated.php
│   │   │   │   └── PublishOrderCancelled.php
│   │   │   ├── Consumers/                     # Kafka consumers for Query Side
│   │   │   │   ├── HandleOrderCreated.php
│   │   │   │   ├── HandleOrderUpdated.php
│   │   │   │   └── HandleOrderCancelled.php
│   │   │   └── KafkaConfig.php                # Kafka-specific configuration
│   │   ├── Workers/                           # Workers for consuming messages
│   │   │   └── KafkaConsumerWorker.php
│   │   ├── Controllers/                       # API and web controllers
│   │   │   ├── OrderController.php
│   │   │   └── Api/
│   │   │       └── OrderApiController.php
│   │   ├── Policies/                          # Authorization policies
│   │   │   └── OrderPolicy.php
│   │   └── Providers/                         # Dependency injection providers
│   │       ├── RepositoryServiceProvider.php
│   │       └── EventBusServiceProvider.php
│   │
│   └── Presentation/
│       └── Http/
│           ├── Requests/                      # HTTP request validation
│           │   ├── CreateOrderRequest.php
│           │   └── UpdateOrderRequest.php
│           ├── Resources/                     # API response formatting
│           │   └── OrderResource.php
│           └── Routes/                        # API routes
│               └── api.php
│
├── tests/                                     # Testing directory
│   ├── Unit/                                  # Unit tests
│   ├── Integration/                           # Integration tests
│   └── EndToEnd/                              # End-to-End tests
│
├── composer.json                              # Composer dependencies
└── README.md                                  # Documentation for the module
```

This Order module structure adheres to a clean and scalable design using principles like DDD, CQRS, and Event-Driven Architecture, making it robust for handling complex business requirements. Here’s an explanation of its key aspects:

### 1. Domain Layer

The Domain layer focuses purely on business logic and rules without dependencies on external services.

- **Aggregates**: Encapsulate related entities and enforce business invariants.
    - *Example*: `OrderAggregate` manages the lifecycle and rules of an order.
- **Entities**: Represent domain concepts with identity.
    - *Example*: `OrderItem` holds details about individual items in an order.
- **Value Objects**: Immutable and value-based, such as `Money` for price or `Address`.
- **Services**: Contain logic that doesn’t naturally belong to entities or aggregates, such as `OrderCalculationService`.
- **Events**: Capture state changes, like `OrderCreatedEvent`. Events are stored and published via `EventStore`.
- **Exceptions**: Handle business-specific errors.

### 2. Application Layer

The Application layer orchestrates domain and infrastructure layers.

- **Use Cases**: Implement specific operations with clear intent. Commands (write) and queries (read) are separated:
    - **Commands**: `CreateOrderCommandRequest` triggers `CreateOrderHandler`.
    - **Queries**: `GetOrderRequest` is processed by `GetOrderHandler`.
- **Services**: Perform application-level coordination.
    - *Example*: `CalculateOrderTotalService` uses domain services and repositories.
- **Interfaces**: Define contracts for infrastructure dependencies (e.g., repositories, event bus).
- **Event Handlers**: Respond to domain events, keeping the system reactive.
- **Exceptions**: Handle application-layer-specific issues like missing orders.

### 3. Infrastructure Layer

The Infrastructure layer implements external dependencies and technical concerns.

- **Persistence**: Separates command (e.g., event-sourced) and query (e.g., MySQL read models) storage mechanisms.
- **Events**: Implements an event bus, using Kafka for distributed messaging. Producers publish domain events, and consumers handle them.
- **Workers**: Kafka consumers that continuously process events.
- **Controllers**: API and web endpoints.
    - *Example*: `OrderController` for server-side logic, and `OrderApiController` for REST API.
- **Policies**: Enforce authorization rules, ensuring secure access.
- **Providers**: Manage dependency injection setup for repositories and the event bus.

### 4. Presentation Layer

The Presentation layer deals with user-facing interactions.

- **Requests**: Handle HTTP input validation for creating or updating orders.
- **Resources**: Format API responses (e.g., transforming domain objects into JSON).
- **Routes**: Define API endpoints in `api.php`.

### 5. Testing

The `tests` directory includes:

- **Unit Tests**: Test isolated pieces of code (e.g., entities, value objects).
- **Integration Tests**: Test interactions between multiple layers.
- **End-to-End Tests**: Simulate real-world scenarios across the entire module.

### Key Advantages

1. **Separation of Concerns**: DDD isolates domain logic, and CQRS decouples reading from writing.
2. **Scalability**: Event-driven architecture supports asynchronous processing and microservices.
3. **Testability**: Layered design makes it easy to test individual components.
4. **Maintainability**: Clear folder structure and use of interfaces simplify future changes.

By following this modular structure, the Order module can evolve independently, ensuring flexibility and maintainability as business requirements change.
