<?php

namespace MahmoudElBoshy\HierarchicalBilling\Traits;

use MahmoudElBoshy\HierarchicalBilling\Models\BillingAccount;
use MahmoudElBoshy\HierarchicalBilling\Enums\BillingModel;
use MahmoudElBoshy\HierarchicalBilling\Enums\BillingStatus;

trait HasSubAccounts
{
    /**
     * Get all sub-accounts under this main account.
     */
    public function subAccounts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BillingAccount::class, 'parent_id');
    }

    /**
     * Create a new sub-account under this main account.
     */
    public function createSubAccount(array $attributes = []): BillingAccount
    {
        $parentAccount = $this->billingAccount;

        if (! $parentAccount) {
            throw new \RuntimeException('Main account must have a billing account before creating sub-accounts.');
        }

        return BillingAccount::create([
            'parent_id'     => $parentAccount->id,
            'billing_model' => BillingModel::SubAccount->value,
            'status'        => BillingStatus::Active,
            'starts_at'     => now(),
            'metadata'      => $attributes['metadata'] ?? null,
            ...$attributes,
        ]);
    }

    /**
     * Get the count of active sub-accounts.
     */
    public function activeSubAccountsCount(): int
    {
        return $this->billingAccount
            ?->subAccounts()
            ->where('status', BillingStatus::Active)
            ->count() ?? 0;
    }

    /**
     * Check if this account has reached the sub-account limit.
     */
    public function hasReachedSubAccountLimit(): bool
    {
        $limit = config('hierarchical-billing.sub_account_limit');

        if (is_null($limit)) {
            return false;
        }

        return $this->activeSubAccountsCount() >= $limit;
    }
}
