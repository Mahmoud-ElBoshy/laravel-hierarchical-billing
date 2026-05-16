<?php

namespace MahmoudElBoshy\HierarchicalBilling\Models;

use Illuminate\Database\Eloquent\Model;

class BilledEvent extends Model
{
    protected $table = 'billed_events';

    protected $fillable = [
        'billing_account_id',
        'event_reference',
        'event_type',
        'amount',
        'currency',
        'status',
        'billed_at',
        'metadata',
    ];

    protected $casts = [
        'amount'    => 'decimal:2',
        'metadata'  => 'array',
        'billed_at' => 'datetime',
    ];

    public function billingAccount(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BillingAccount::class);
    }

    public function transaction(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BillingTransaction::class);
    }
}
