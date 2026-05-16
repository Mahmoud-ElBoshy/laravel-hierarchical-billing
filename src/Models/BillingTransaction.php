<?php

namespace MahmoudElBoshy\HierarchicalBilling\Models;

use Illuminate\Database\Eloquent\Model;

class BillingTransaction extends Model
{
    protected $table = 'billing_transactions';

    protected $fillable = [
        'billing_account_id',
        'billable_type',
        'billable_id',
        'amount',
        'currency',
        'status',
        'type',
        'reference',
        'gateway_response',
        'metadata',
        'paid_at',
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'gateway_response' => 'array',
        'metadata'         => 'array',
        'paid_at'          => 'datetime',
    ];

    public function billingAccount(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BillingAccount::class);
    }

    public function billable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
