<?php

namespace Qtvhao\OrderModule\Application\Interfaces;

/**
 * Interface EventBusInterface
 * Defines a contract for event handling and dispatching.
 */
interface EventBusInterface
{
    /**
     * Publish a single event to the event bus.
     *
     * @param object $event
     * @return void
     */
    public function publish(object $event): void;

    /**
     * Publish multiple events to the event bus.
     *
     * @param array $events
     * @return void
     */
    public function publishMultiple(array $events): void;

    /**
     * Subscribe a listener to handle specific types of events.
     *
     * @param string $eventClass The event class name.
     * @param callable $listener The listener to handle the event.
     * @return void
     */
    public function subscribe(string $eventClass, callable $listener): void;
}
