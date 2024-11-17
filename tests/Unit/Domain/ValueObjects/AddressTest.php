<?php
namespace Qtvhao\OrderModule\Tests\Unit\Domain\ValueObjects;
use Error;
use Qtvhao\OrderModule\Domain\ValueObjects\Address;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase {
    public function testCanCreateAddress() {
        $address = new Address("123 Main St", "Hanoi", "100000", "Vietnam");

        $this->assertEquals("123 Main St", $address->getStreet());
        $this->assertEquals("Hanoi", $address->getCity());
        $this->assertEquals("100000", $address->getZipcode());
        $this->assertEquals("Vietnam", $address->getCountry());
    }

    public function testAddressEquality() {
        $address1 = new Address("123 Main St", "Hanoi", "100000", "Vietnam");
        $address2 = new Address("123 Main St", "Hanoi", "100000", "Vietnam");
        $address3 = new Address("456 Elm St", "Hanoi", "100000", "Vietnam");

        $this->assertTrue($address1->equals($address2));
        $this->assertFalse($address1->equals($address3));
    }

    public function testImmutability() {
        $address = new Address("123 Main St", "Hanoi", "100000", "Vietnam");
        
        // Try modifying properties (they should be private and have no setters)
        $this->expectException(Error::class);
        $address->street = "New Street";
    }
}
