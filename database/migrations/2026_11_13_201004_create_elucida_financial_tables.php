<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. The Macro Ledger (Financial Report Period)
        Schema::create('elucida_ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('period_name'); // e.g., "Second Semester - Academic Year 2025-2026"
            $table->decimal('beginning_bank', 12, 2)->default(0);
            $table->decimal('beginning_cash', 12, 2)->default(0);
            $table->decimal('ending_bank', 12, 2)->nullable();
            $table->decimal('ending_cash', 12, 2)->nullable();
            $table->string('status')->default('draft'); // draft, pending_approval, approved
            $table->timestamps();
        });

        // 2. The Unified Transaction Ledger (Inflows and Outflows)
        Schema::create('elucida_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ledger_id')->constrained('elucida_ledgers')->cascadeOnDelete();
            $table->enum('type', ['inflow', 'outflow']);
            $table->string('category'); // e.g., 'Internal Funds', 'Membership Fee', 'Operational Expense', 'Events'
            $table->string('title'); // e.g., "19th CircUITS General Assembly"
            $table->decimal('amount', 12, 2);
            $table->string('reference_note')->nullable(); // e.g., "Note 1", "LR015"
            $table->date('transaction_date')->nullable();
            $table->timestamps();
        });

        // 3. The Liquidation Workflow (Tied to an outflow transaction)
        Schema::create('elucida_liquidations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->nullable()->constrained('elucida_transactions')->nullOnDelete();
            $table->foreignId('accountable_officer_id')->constrained('users'); // Links to your main users table
            $table->string('dv_number')->unique(); // e.g., "CircUITS-DV-2026-016"
            $table->string('lr_tracker_number')->unique(); // e.g., "CircUITS-2026-LR-014"
            $table->decimal('cash_advance', 12, 2)->default(0);
            $table->date('implementation_start')->nullable();
            $table->date('implementation_end')->nullable();
            $table->date('report_date')->nullable();
            $table->string('status')->default('pending'); // pending, certified, approved
            $table->timestamps();
        });

        // 4. The Itemized Liquidation Expenses
        Schema::create('elucida_liquidation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('liquidation_id')->constrained('elucida_liquidations')->cascadeOnDelete();
            $table->string('expense_category'); // e.g., "Supplies and Materials Expenses"
            $table->integer('qty')->default(1);
            $table->string('particulars'); // e.g., "Bond Paper - 1 ream"
            $table->date('date_incurred');
            $table->string('payee'); // e.g., "Baytown Commercial complex"
            $table->string('receipt_number')->nullable(); // OR/SI #
            $table->decimal('amount', 12, 2);
            $table->timestamps();
        });

        // 5. The High-Volume Sales Register
        Schema::create('elucida_sales_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->nullable()->constrained('elucida_transactions')->cascadeOnDelete();
            $table->string('event_name'); // e.g., "IT DAYS - CHAROT READING"
            $table->string('buyer_name');
            $table->string('buyer_block')->nullable(); // e.g., "BSIT 1A"
            $table->string('receipt_number')->unique();
            $table->decimal('price', 10, 2);
            $table->date('sale_date')->nullable();
            $table->timestamps();
        });

        // 6. The Polymorphic Signatory Workflow
        Schema::create('elucida_signatories', function (Blueprint $table) {
            $table->id();
            $table->morphs('signable'); // Creates signable_id and signable_type (for Ledgers, Liquidations, etc.)
            $table->foreignId('user_id')->nullable()->constrained('users'); // Nullable if signatory is an external guest
            $table->string('name_override')->nullable(); // E.g. "JOCELYN E. SERRANO, M.Sc"
            $table->string('designation'); // e.g., "Dean, BU College of Science"
            $table->enum('role', ['prepared_by', 'certified', 'attested', 'noted', 'recommending', 'approved']);
            $table->boolean('has_signed')->default(false);
            $table->timestamp('signed_at')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('hierarchy_order')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elucida_signatories');
        Schema::dropIfExists('elucida_sales_registers');
        Schema::dropIfExists('elucida_liquidation_items');
        Schema::dropIfExists('elucida_liquidations');
        Schema::dropIfExists('elucida_transactions');
        Schema::dropIfExists('elucida_ledgers');
    }
};
