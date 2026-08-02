<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name', 150);
            $table->string('account_name', 150);
            $table->string('account_number', 100)->unique();
            $table->string('branch', 150)->nullable();
            $table->decimal('balance', 12, 2)->default(0.00);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_account_id')->constrained('bank_accounts')->onDelete('cascade');
            $table->enum('type', ['deposit', 'withdrawal', 'transfer']);
            $table->decimal('amount', 12, 2);
            $table->string('category', 100);
            $table->string('reference', 100)->nullable();
            $table->date('transaction_date');
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('category', 100);
            $table->decimal('amount', 10, 2);
            $table->string('description', 255)->nullable();
            $table->date('expense_date');
            $table->string('receipt_path', 255)->nullable();
            $table->foreignId('bank_account_id')->nullable()->constrained('bank_accounts')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->string('category', 100);
            $table->decimal('amount', 10, 2);
            $table->string('description', 255)->nullable();
            $table->date('income_date');
            $table->foreignId('bank_account_id')->nullable()->constrained('bank_accounts')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('bank_transactions');
        Schema::dropIfExists('bank_accounts');
    }
};
