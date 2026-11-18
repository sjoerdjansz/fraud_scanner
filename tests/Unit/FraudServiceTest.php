<?php

namespace Tests\Unit;

use App\Enums\FraudReason;
use App\Models\Customer;
use App\Services\FraudService;
use Tests\TestCase;

/**
 * Tests the fraud rules from the assignment, without the API or the database.
 *
 * Extends Laravel's TestCase (not PHPUnit's) because the Customer model
 * needs the app to cast date_of_birth to a date.
 */
class FraudServiceTest extends TestCase
{
    public function test_customers_sharing_an_iban_are_both_flagged(): void
    {
        $first = $this->customer(['iban' => 'NL01BANK0000000001']);
        $second = $this->customer(['iban' => 'NL01BANK0000000001']);
        $other = $this->customer(['iban' => 'NL02BANK0000000002']);

        (new FraudService)->scanFraudulentCustomers([$first, $second, $other]);

        $this->assertSame([FraudReason::SharedIban], $first->fraud_reasons->toArray());
        $this->assertSame([FraudReason::SharedIban], $second->fraud_reasons->toArray());
        $this->assertCount(0, $other->fraud_reasons);
    }

    public function test_customers_sharing_an_ip_address_are_both_flagged(): void
    {
        $first = $this->customer(['ipAddress' => '10.0.0.1']);
        $second = $this->customer(['ipAddress' => '10.0.0.1']);
        $other = $this->customer(['ipAddress' => '10.0.0.2']);

        (new FraudService)->scanFraudulentCustomers([$first, $second, $other]);

        $this->assertSame([FraudReason::SharedIp], $first->fraud_reasons->toArray());
        $this->assertSame([FraudReason::SharedIp], $second->fraud_reasons->toArray());
        $this->assertCount(0, $other->fraud_reasons);
    }

    /**
     * The API sometimes leaves fields out. Two customers that both have no IBAN
     * or IP should not count as "sharing" one.
     */
    public function test_missing_iban_and_ip_do_not_count_as_shared(): void
    {
        $first = $this->customer();
        $second = $this->customer();

        (new FraudService)->scanFraudulentCustomers([$first, $second]);

        $this->assertCount(0, $first->fraud_reasons);
        $this->assertCount(0, $second->fraud_reasons);
    }

    public function test_a_phone_number_from_outside_the_netherlands_is_flagged(): void
    {
        $dutch = $this->customer(['phoneNumber' => '+31612345678']);
        $belgian = $this->customer(['phoneNumber' => '+32470123456']);

        (new FraudService)->scanFraudulentCustomers([$dutch, $belgian]);

        $this->assertCount(0, $dutch->fraud_reasons);
        $this->assertSame([FraudReason::ForeignPhoneNumber], $belgian->fraud_reasons->toArray());
    }

    /**
     * Tests the boundary: someone who turns 18 today is no longer underage.
     */
    public function test_a_customer_younger_than_18_is_flagged(): void
    {
        $seventeen = $this->customer(['dateOfBirth' => now()->subYears(17)->format('d-m-Y')]);
        $eighteenToday = $this->customer(['dateOfBirth' => now()->subYears(18)->format('d-m-Y')]);

        (new FraudService)->scanFraudulentCustomers([$seventeen, $eighteenToday]);

        $this->assertSame([FraudReason::Underage], $seventeen->fraud_reasons->toArray());
        $this->assertCount(0, $eighteenToday->fraud_reasons);
    }

    /**
     * Builds a customer that is NOT fraudulent by default (Dutch phone number, adult).
     * IBAN and IP are left empty on purpose, so customers never share them by accident.
     */
    private function customer(array $overrides = []): Customer
    {
        return Customer::fromApi(array_merge([
            'phoneNumber' => '+31612345678',
            'dateOfBirth' => '01-01-1990',
        ], $overrides));
    }
}
