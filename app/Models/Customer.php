<?php

namespace App\Models;

use App\Enums\FraudReason;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\AsEnumArrayObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['scan_id', 'external_customer_id', 'first_name', 'last_name', 'email', 'date_of_birth', 'phone', 'ip', 'iban', 'products', 'fraud_reasons'])]
class Customer extends Model
{
    const null UPDATED_AT = null;

    /**
     * @return BelongsTo<Scan, $this>
     */
    public function scan(): BelongsTo
    {
        return $this->belongsTo(Scan::class);
    }

    public static function fromApi(array $data): Customer
    {

        $dateOfBirth = null;
        $rawData = $data['dateOfBirth'] ?? null;

        if (is_string($rawData) && Carbon::canBeCreatedFromFormat($rawData, 'd-m-Y')) {
            $parsed = Carbon::createFromFormat('d-m-Y', $rawData)->startOfDay();
            if ($parsed->format('d-m-Y') === $rawData) {
                $dateOfBirth = $parsed;
            }
        }

        return new Customer([
            'external_customer_id' => $data['customerId'] ?? null,
            'first_name' => $data['firstName'] ?? null,
            'last_name' => $data['lastName'] ?? null,
            'email' => $data['email'] ?? null,
            'date_of_birth' => $dateOfBirth,
            'phone' => $data['phoneNumber'] ?? null,
            'ip' => $data['ipAddress'] ?? null,
            'iban' => $data['iban'] ?? null,
            'products' => $data['products'] ?? [],
            'fraud_reasons' => [],
        ]);
    }

    protected function casts(): array
    {
        return [
            'external_customer_id' => 'integer',
            'date_of_birth' => 'date',
            'fraud_reasons' => AsEnumArrayObject::of(FraudReason::class),
            'products' => 'array',
        ];
    }
}
