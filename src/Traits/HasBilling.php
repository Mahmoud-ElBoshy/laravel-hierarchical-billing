<?php

namespace MahmoudElBoshy\HierarchicalBilling\Traits;

use MahmoudElBoshy\HierarchicalBilling\Models\BillingAccount;
use MahmoudElBoshy\HierarchicalBilling\Models\BillingTransaction;
use MahmoudElBoshy\HierarchicalBilling\Enums\BillingStatus;

trait HasBilling
{
    /**
     * Get the billing account associated with this model.
     */
    public function billingAccount(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(BillingAccount::class, 'billable');
    }

    /**
     * Get all billing transactions for this model.
     */
    public function billingTransactions(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(BillingTransaction::class, 'billable');
    }

    /**
     * Check if this model has an active subscription.
     */
    public function isSubscribed(): bool
    {
        return $this->billingAccount?->status === BillingStatus::Active;
    }

    /**
     * Subscribe to a plan.
     */
    public function subscribe(string $planId, array $options = []): BillingAccount
    {
        $account = $this->billingAccount()->firstOrCreate([
            'billable_type' => get_class($this),
            'billable_id'   => $this->getKey(),
        ]);

        $account->update([
            'plan_id'    => $planId,
            'status'     => BillingStatus::Active,
            'starts_at'  => now(),
            'ends_at'    => $options['ends_at'] ?? null,
            'metadata'   => $options['metadata'] ?? null,
        ]);

        return $account->fresh();
    }

    /**
     * Cancel the subscription.
     */
    public function cancelSubscription(): bool
    {
        return (bool) $this->billingAccount?->update([
            'status'       => BillingStatus::Cancelled,
            'cancelled_at' => now(),
        ]);
    }

    /**
     * Suspend the account.
     */
    public function suspendBilling(): bool
    {
        return (bool) $this->billingAccount?->update([
            'status' => BillingStatus::Suspended,
        ]);
    }

    /**
     * Get the billing model type for this entity.
     */
    public function getBillingModel(): string
    {
        return $this->billingAccount?->billing_model ?? config('hierarchical-billing.default_model');
    }

    /**
     * Check if billing is in a specific status.
     */
    public function hasBillingStatus(BillingStatus $status): bool
    {
        return $this->billingAccount?->status === $status;
    }
}
