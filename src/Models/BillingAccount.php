<?php

namespace MahmoudElBoshy\HierarchicalBilling\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MahmoudElBoshy\HierarchicalBilling\Enums\BillingModel;
use MahmoudElBoshy\HierarchicalBilling\Enums\BillingStatus;

class BillingAccount extends Model
{
    use SoftDeletes;

    protected $table = 'billing_accounts';

    protected $fillable = [
        'billable_type',
        'billable_id',
        'parent_id',
        'billing_model',
        'plan_id',
        'status',
        'starts_at',
        'ends_at',
        'cancelled_at',
        'trial_ends_at',
        'metadata',
    ];

    protected $casts = [
        'billing_model' => BillingModel::class,
        'status'        => BillingStatus::class,
        'starts_at'     => 'datetime',
        'ends_at'       => 'datetime',
        'cancelled_at'  => 'datetime',
        'trial_ends_at' => 'datetime',
        'metadata'      => 'array',
    ];

    /**
     * The billable model (User, Organization, etc.)
     */
    public function billable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Parent billing account (for sub-accounts)
     */
    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BillingAccount::class, 'parent_id');
    }

    /**
     * Sub-accounts under this account
     */
    public function subAccounts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BillingAccount::class, 'parent_id');
    }

    /**
     * Billing transactions for this account
     */
    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BillingTransaction::class, 'billing_account_id');
    }

    /**
     * Events billed under this account
     */
    public function billedEvents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BilledEvent::class, 'billing_account_id');
    }

    /**
     * Check if account is active
     */
    public function isActive(): bool
    {
        return $this->status === BillingStatus::Active;
    }

    /**
     * Check if account is on trial
     */
    public function onTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    /**
     * Check if subscription has expired
     */
    public function hasExpired(): bool
    {
        return $this->ends_at && $this->ends_at->isPast();
    }

    /**
     * Check if this is a sub-account
     */
    public function isSubAccount(): bool
    {
        return ! is_null($this->parent_id);
    }
}
