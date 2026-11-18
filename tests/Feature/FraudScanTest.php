<?php

namespace Tests\Feature;

use App\Enums\FraudReason;
use App\Models\Customer;
use App\Models\Scan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Tests the whole scan flow: POST /scans -> fetch customers -> check fraud -> save.
 *
 * Http::fake() replaces the real Customers API, so the tests are fast and always
 * give the same result. preventStrayRequests() makes a test fail if a request
 * reaches the real API anyway.
 */
class FraudScanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }

    public function test_a_scan_is_saved_with_its_customers_and_fraud_flags(): void
    {
        Http::fake([
            '*/api/v1/customers' => Http::response([
                'success' => true,
                'customers' => [
                    ['customerId' => 1, 'phoneNumber' => '+31600000001', 'iban' => 'NL01SAME', 'ipAddress' => '10.0.0.1'],
                    ['customerId' => 2, 'phoneNumber' => '+31600000002', 'iban' => 'NL01SAME', 'ipAddress' => '10.0.0.2'],
                    ['customerId' => 3, 'phoneNumber' => '+31600000003', 'iban' => 'NL03OTHER', 'ipAddress' => '10.0.0.3'],
                ],
            ]),
        ]);

        $response = $this->post('/scans');

        $scan = Scan::first();
        $response->assertRedirect(route('scans.show', $scan));
        $this->assertCount(3, $scan->customers);

        // Customers 1 and 2 share an IBAN, customer 3 is clean.
        $this->assertSame([FraudReason::SharedIban], Customer::firstWhere('external_customer_id', 1)->fraud_reasons->toArray());
        $this->assertSame([FraudReason::SharedIban], Customer::firstWhere('external_customer_id', 2)->fraud_reasons->toArray());
        $this->assertCount(0, Customer::firstWhere('external_customer_id', 3)->fraud_reasons);
    }

    /**
     * The API is unstable on purpose. One failed request should not break the scan:
     * CustomerService retries, so a scan is still saved if a later attempt succeeds.
     */
    public function test_a_scan_still_succeeds_when_the_api_fails_once(): void
    {
        Http::fake([
            '*/api/v1/customers' => Http::sequence()
                ->push('Internal Server Error', 500)
                ->push([
                    'success' => true,
                    'customers' => [
                        ['customerId' => 1, 'phoneNumber' => '+31600000001'],
                    ],
                ]),
        ]);

        $response = $this->post('/scans');

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount('scans', 1);
        $this->assertDatabaseCount('customers', 1);
    }

    /**
     * The API sometimes returns junk inside the customers list.
     * Those entries are skipped; the valid customers are still saved.
     */
    public function test_entries_that_are_not_customers_are_skipped(): void
    {
        Http::fake([
            '*/api/v1/customers' => Http::response([
                'success' => true,
                'customers' => [
                    ['customerId' => 1, 'phoneNumber' => '+31600000001'],
                    'not a customer',
                    null,
                ],
            ]),
        ]);

        $this->post('/scans');

        $this->assertDatabaseCount('scans', 1);
        $this->assertDatabaseCount('customers', 1);
    }

    public function test_no_scan_is_saved_when_the_api_returns_an_error(): void
    {
        Http::fake([
            '*/api/v1/customers' => Http::response('Internal Server Error', 500),
        ]);

        $response = $this->from('/')->post('/scans');

        $response->assertRedirect('/');
        $response->assertSessionHasErrors('scan');
        $this->assertDatabaseCount('scans', 0);
    }

    /**
     * Status 200 does not mean the data is usable. Here the API says success: false.
     */
    public function test_no_scan_is_saved_when_the_api_returns_invalid_data(): void
    {
        Http::fake([
            '*/api/v1/customers' => Http::response(['success' => false]),
        ]);

        $response = $this->from('/')->post('/scans');

        $response->assertRedirect('/');
        $response->assertSessionHasErrors('scan');
        $this->assertDatabaseCount('scans', 0);
    }
}
