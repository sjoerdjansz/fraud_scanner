<?php

namespace App\Enums;

enum FraudReason: string
{
    case SharedIban = 'shared_iban';
    case ForeignPhoneNumber = 'foreign_phone_number';
    case SharedIp = 'shared_ip';
    case Underage = 'underage';

    public function fraudReasonLabel(): string
    {
        return match ($this) {
            self::SharedIban => 'IBAN is shared between customers',
            self::ForeignPhoneNumber => 'Mobile phone number is not Dutch',
            self::SharedIp => 'IP address is shared between customers',
            self::Underage => 'Customer is younger than 18',
        };
    }
}
