<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Billing Model
    |--------------------------------------------------------------------------
    | Options: subscription | per_event | sub_account
    */
    'default_model' => env('BILLING_DEFAULT_MODEL', 'subscription'),

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    */
    'currency' => env('BILLING_CURRENCY', 'SAR'),

    /*
    |--------------------------------------------------------------------------
    | Sub-Account Limit
    |--------------------------------------------------------------------------
    | Maximum number of sub-accounts allowed per main account.
    | Set to null for unlimited.
    */
    'sub_account_limit' => env('BILLING_SUB_ACCOUNT_LIMIT', null),

    /*
    |--------------------------------------------------------------------------
    | Trial Period (days)
    |--------------------------------------------------------------------------
    */
    'trial_days' => env('BILLING_TRIAL_DAYS', 14),

    /*
    |--------------------------------------------------------------------------
    | Grace Period (days)
    |--------------------------------------------------------------------------
    | Number of days after subscription expires before account is suspended.
    */
    'grace_period_days' => env('BILLING_GRACE_PERIOD_DAYS', 3),

];
