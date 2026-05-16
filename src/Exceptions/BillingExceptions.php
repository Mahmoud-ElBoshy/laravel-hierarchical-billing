<?php

namespace MahmoudElBoshy\HierarchicalBilling\Exceptions;

class BillingException extends \RuntimeException {}

class SubAccountLimitReachedException extends BillingException
{
    public function __construct()
    {
        parent::__construct('Sub-account limit has been reached for this account.');
    }
}

class NoBillingAccountException extends BillingException
{
    public function __construct()
    {
        parent::__construct('No billing account found. Please subscribe first.');
    }
}

class InvalidBillingModelException extends BillingException
{
    public function __construct(string $model)
    {
        parent::__construct("Invalid billing model: {$model}");
    }
}
