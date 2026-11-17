<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomerException;
use App\Models\Customer;
use App\Services\CustomerService;
use App\Services\FraudService;
use Illuminate\Http\RedirectResponse;
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

        $this->fraudService->scanFraudulentCustomers($customers);

        return Inertia::render('FraudScanner', [
            'customers' => $customers,
        ]);
    }
}
