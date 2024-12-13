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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('property_id')->constrained('property')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('property_units')->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('lease_id')->constrained('leases')->onDelete('cascade');
            $table->string('lease_number');
            $table->string('invoice_number')->unique();
            $table->string('invoice_month');
            $table->timestamp('invoice_end_date');
            $table->double('total_amount');
            $table->string('remarks')->nullable();
            $table->enum('status', ['OPEN', 'PARTIALLY_PAID', 'PAID']);
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
        Schema::dropIfExists('invoices');
    }
};
