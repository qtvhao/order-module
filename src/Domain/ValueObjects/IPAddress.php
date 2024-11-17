<?php
namespace Qtvhao\OrderModule\Domain\ValueObjects;

use InvalidArgumentException;

class IPAddress
{
    private string $ip;

    public function __construct(string $ip)
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new InvalidArgumentException("Invalid IP address: {$ip}");
        }
        $this->ip = $ip;
    }

    public function getIPAddress(): string
    {
        return $this->ip;
    }

    public function equals(IPAddress $ip): bool
    {
        return $this->ip === $ip->getIPAddress();
    }
}