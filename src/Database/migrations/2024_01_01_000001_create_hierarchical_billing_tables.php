<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_accounts', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('billable');
            $table->foreignId('parent_id')->nullable()->constrained('billing_accounts')->nullOnDelete();
            $table->string('billing_model')->default('subscription'); // subscription | per_event | sub_account
            $table->string('plan_id')->nullable();
            $table->string('status')->default('active');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->json('metadata')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('billing_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('billing_account_id')->constrained()->cascadeOnDelete();
            $table->nullableMorphs('billable');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('SAR');
            $table->string('status')->default('pending'); // pending | paid | failed | refunded
            $table->string('type')->default('charge');    // charge | refund | adjustment
            $table->string('reference')->nullable()->unique();
            $table->json('gateway_response')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('billed_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('billing_account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('billing_transaction_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event_reference');
            $table->string('event_type')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('SAR');
            $table->string('status')->default('pending');
            $table->json('metadata')->nullable();
            $table->timestamp('billed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billed_events');
        Schema::dropIfExists('billing_transactions');
        Schema::dropIfExists('billing_accounts');
    }
};
