<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('property_id');
            $table->bigInteger('unit_id');
            $table->bigInteger('tenant_id');
            $table->bigInteger('lease_id');
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
            $table->foreign('unit_id')->references('id')->on('property_units')->onDelete('cascade');
            $table->foreign('property_id')->references('id')->on('property')->onDelete('cascade');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('lease_id')->references('id')->on('leases')->onDelete('cascade');
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
