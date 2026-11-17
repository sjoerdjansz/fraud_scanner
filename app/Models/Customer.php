<?php

namespace App\Models;

use App\Enums\FraudReason;
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
