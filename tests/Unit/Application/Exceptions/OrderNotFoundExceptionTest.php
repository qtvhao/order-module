<?php

namespace Tests\Unit\Application\Exceptions;

use PHPUnit\Framework\TestCase;
use Qtvhao\OrderModule\Application\Exceptions\OrderNotFoundException;

class OrderNotFoundExceptionTest extends TestCase
{
    /**
     * Test that OrderNotFoundException extends the base Exception class.
     */
    public function test_it_extends_base_exception(): void
    {
        $exception = new OrderNotFoundException();
        $this->assertInstanceOf(\Exception::class, $exception);
    }

    /**
     * Test that the exception has the correct default message.
     */
    public function test_it_has_default_message(): void
    {
        $exception = new OrderNotFoundException();
        $this->assertEquals('Order not found.', $exception->getMessage());
    }

    /**
     * Test that the exception can have a custom message.
     */
    public function test_it_allows_custom_message(): void
    {
        $message = 'The specified order does not exist.';
        $exception = new OrderNotFoundException($message);
        $this->assertEquals($message, $exception->getMessage());
    }

    /**
     * Test that the exception has a default error code of 404.
     */
    public function test_it_has_default_code(): void
    {
        $exception = new OrderNotFoundException();
        $this->assertEquals(404, $exception->getCode());
    }

    /**
     * Test that the exception can have a custom error code.
     */
    public function test_it_allows_custom_code(): void
    {
        $code = 500;
        $exception = new OrderNotFoundException('Custom message', $code);
        $this->assertEquals($code, $exception->getCode());
    }
}
