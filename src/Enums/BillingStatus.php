<?php

namespace MahmoudElBoshy\HierarchicalBilling\Enums;

enum BillingStatus: string
{
    case Active    = 'active';
    case Inactive  = 'inactive';
    case Suspended = 'suspended';
    case Cancelled = 'cancelled';
    case PastDue   = 'past_due';
}
