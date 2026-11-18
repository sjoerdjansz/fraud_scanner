<?php

namespace Tests\Unit;

use App\Models\Customer;
use Tests\TestCase;

/**
 * Tests how Customer::fromApi() handles the (sometimes strange) data from the API.
 */
class CustomerTest extends TestCase
{
    public function test_a_valid_date_of_birth_is_parsed(): void
    {
        $customer = Customer::fromApi(['dateOfBirth' => '20-07-1996']);

        $this->assertSame('1996-07-20', $customer->date_of_birth->toDateString());
    }

    /**
     * An invalid date must become null instead of a wrong date.
     * '31-02-2000' is the tricky one: Carbon would quietly turn it into 2 March.
     */
    public function test_an_invalid_date_of_birth_becomes_null(): void
    {
        $invalidDates = ['31-02-2000', '2000-01-01', 'not a date', '', null, 12345];

        foreach ($invalidDates as $invalidDate) {
            $customer = Customer::fromApi(['dateOfBirth' => $invalidDate]);

            $this->assertNull($customer->date_of_birth, 'Expected null for: '.var_export($invalidDate, true));
        }
    }
}
