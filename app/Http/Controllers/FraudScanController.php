<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomerException;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class FraudScanController extends Controller
{
    public function __construct(
        private CustomerService $customerService,
        //        private FraudService $fraudService,
    ) {}

    public function __invoke(): Response|RedirectResponse
    {
        try {
            $apiCustomers = $this->customerService->fetchCustomers();
        } catch (CustomerException $exception) {
            return back()->withErrors(['scans.show' => $exception->getMessage()]);
        }

        $customers = [];

        foreach ($apiCustomers as $apiCustomer) {
            if (! is_array($apiCustomer)) {
                continue;
            }
            $customers[] = Customer::fromApi($apiCustomer);
        }

        // $fraudulentCustomers = $this->fraudService->scanFraudulentCustomers();

        return Inertia::render('FraudScanner', [
            'customers' => $customers,
        ]);
    }
}
