<?php

namespace MahmoudElBoshy\HierarchicalBilling\Contracts;

interface Billable
{
    public function billingAccount(): \Illuminate\Database\Eloquent\Relations\MorphOne;
    public function isSubscribed(): bool;
    public function subscribe(string $planId, array $options = []): mixed;
    public function cancelSubscription(): bool;
    public function getBillingModel(): string;
}
