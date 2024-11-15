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
│   │   │   ├── Money.php
│   │   │   └── OrderStatus.php
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
