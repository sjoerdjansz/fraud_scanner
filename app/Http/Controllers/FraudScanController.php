<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomerException;
use App\Models\Customer;
use App\Models\Scan;
use App\Services\CustomerService;
use App\Services\FraudService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class FraudScanController extends Controller
{
    public function __construct(
        private CustomerService $customerService,
        private FraudService $fraudService,
    ) {}

    public function __invoke(): Response|RedirectResponse
    {
        try {
            $apiCustomers = $this->customerService->fetchCustomers();
        } catch (CustomerException $exception) {
            return back()->withErrors(['scan' => $exception->getMessage()]);
        }

        $customers = [];

        foreach ($apiCustomers as $apiCustomer) {
            if (! is_array($apiCustomer)) {
                continue;
            }
            $customers[] = Customer::fromApi($apiCustomer);
        }

        $scannedCustomers = $this->fraudService->scanFraudulentCustomers($customers);

        $scan = DB::transaction(function () use ($scannedCustomers) {
            $scan = Scan::create();
            $scan->customers()->saveMany($scannedCustomers);

            return $scan;
        });

        return Inertia::render('FraudScanner', [
            'customers' => $customers,
        ]);
    }
}
