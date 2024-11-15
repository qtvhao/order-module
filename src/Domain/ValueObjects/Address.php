<?php
namespace Qtvhao\OrderModule\Domain\ValueObjects;

class Address {
    private string $street;
    private string $city;
    private string $zipcode;
    private string $country;

    public function __construct(string $street, string $city, string $zipcode, string $country) {
        $this->street = $street;
        $this->city = $city;
        $this->zipcode = $zipcode;
        $this->country = $country;
    }

    public function getStreet(): string { return $this->street; }
    public function getCity(): string { return $this->city; }
    public function getZipcode(): string { return $this->zipcode; }
    public function getCountry(): string { return $this->country; }

    public function equals(Address $address): bool {
        return $this->street === $address->street &&
               $this->city === $address->city &&
               $this->zipcode === $address->zipcode &&
               $this->country === $address->country;
    }
}
