<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'gateway_transaction_id',
        'gateway_status',
        'response_code',
        'date',
        'payment_id',
        'url',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function gatewayTransactionStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentGatewayTransactionStatus::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
