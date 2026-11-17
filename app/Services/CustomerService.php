<?php

namespace App\Services;

use App\Exceptions\CustomerException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CustomerService
{
    private string $url;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->url = config('services.customers.url');
    }

    /**
     * @throws CustomerException
     */
    public function fetchCustomers(): array
    {
        try {
            $result = Http::acceptJson()
                ->baseUrl($this->url)
                ->timeout(6)
                ->retry(3, 100)
                ->throw()
                ->get('/api/v1/customers');
        } catch (ConnectionException|RequestException $exception) {
            Log::error('Failed request', ['exception' => $exception]);
            throw new CustomerException("Couldn't reach the Customers API", previous: $exception);
        }
        $customers = $result->json('customers');

        // API response is successful but the contents are invalid
        if ($result->json('success') !== true || ! is_array($customers)) {
            Log::error('Invalid response', ['body' => $result->body()]);
            throw new CustomerException('Invalid response data from Customers API');
        }

        return $customers;
    }
}

//   app(\App\Services\CustomerService::class)->fetchCustomers();
