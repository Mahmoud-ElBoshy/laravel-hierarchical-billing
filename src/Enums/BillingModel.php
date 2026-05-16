<?php

namespace MahmoudElBoshy\HierarchicalBilling\Enums;

enum BillingModel: string
{
    case Subscription = 'subscription';
    case PerEvent     = 'per_event';
    case SubAccount   = 'sub_account';
}
