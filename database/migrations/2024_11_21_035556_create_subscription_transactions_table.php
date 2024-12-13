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
        Schema::create('subscription_transactions', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->bigInteger('user_id')->constrained('users');
            $table->bigInteger('package_id')->constrained('subscription_packages');
            $table->double('amount');
            $table->string('payment_type');
            $table->enum('payment_status',['SUCCESS','FAILED','PENDING','CANCELLED']); // Enum-like values: 1=Success, 2=Failed, 3=Pending, 4=Cancelled
            $table->string('receipt')->nullable();
            $table->date('created_at')->useCurrent();
            $table->date('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('modified_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_transactions');
    }
};
