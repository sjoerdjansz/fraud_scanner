<?php

namespace Tests\Feature;

use App\Enums\FraudReason;
use App\Models\Scan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

/**
 * Tests that the Vue pages get the saved scans and customers as props.
 * assertInertia() checks which Vue page is rendered and which data it receives.
 */
class ScanPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_overview_shows_all_scans(): void
    {
        Scan::create();
        Scan::create();

        $response = $this->get('/scans');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('scans/Scans')
            ->has('scans', 2)
        );
    }

    public function test_a_scan_page_shows_its_customers_with_the_fraud_reason(): void
    {
        $scan = Scan::create();
        $scan->customers()->create([
            'first_name' => 'Jan',
            'fraud_reasons' => [FraudReason::Underage],
        ]);

        $response = $this->get(route('scans.show', $scan));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('scans/Show')
            ->has('customers', 1)
            ->where('customers.0.first_name', 'Jan')
            ->where('customers.0.fraud_reason_labels', ['Customer is younger than 18'])
        );
    }
}
