<?php

namespace App\Services;

use App\Enums\FraudReason;
use App\Models\Customer;
use Carbon\CarbonInterface;

class FraudService
{
    /**
     * @param  array<int, Customer>  $customers
     * @return array<int, Customer>
     *
     * */
    public function scanFraudulentCustomers(array $customers): array
    {
        $fraudReasons = [];

        foreach ($customers as $i => $customer) {
            $fraudReasons[$i] = [];

            if ($this->checkPhoneNumber($customer->phone)) {
                $fraudReasons[$i][] = FraudReason::ForeignPhoneNumber;
            }
            if ($this->isUnderage($customer->date_of_birth)) {
                $fraudReasons[$i][] = FraudReason::Underage;
            }
        }

        foreach ($this->findDuplicates($customers, 'iban') as $i) {
            $fraudReasons[$i][] = FraudReason::SharedIban;
        }

        foreach ($this->findDuplicates($customers, 'ip') as $i) {
            $fraudReasons[$i][] = FraudReason::SharedIp;
        }

        foreach ($customers as $i => $customer) {
            $customer->fraud_reasons = $fraudReasons[$i];
        }

        return $customers;
    }

    private function checkPhoneNumber(mixed $phone): bool
    {
        if (! is_string($phone) || trim($phone) === '') {
            return false;
        }

        $isDutch = str_starts_with($phone, '+31');

        return ! $isDutch;
    }

    private function isUnderage(?CarbonInterface $dateOfBirth): bool
    {
        return $dateOfBirth !== null && $dateOfBirth->age < 18;
    }

    /**
     * @param  array<int, Customer>  $customers
     * @return array<int, int>
     *
     * */
    private function findDuplicates(array $customers, string $attribute): array
    {
        $values = collect($customers)->map(fn (Customer $customer) => $customer->{$attribute})->filter();
        $amount = $values->countBy();

        return $values->filter(fn ($value) => $amount[$value] > 1)->keys()->all();
    }
}
