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
        Schema::create('leases', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('property_id')->constrained('property')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('property_units')->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('lease_number')->unique();
            $table->timestamp('rent_start_date');
            $table->timestamp('rent_end_date');
            $table->double('rent_amount');
            $table->enum('rent_type', ['Daily', 'Weekly', 'Monthly', 'Yearly']);
            $table->integer('terms');
            $table->double('deposit_amount');
            $table->string('deposit_description')->nullable();
            $table->string('contract');
            $table->enum('status',['DRAFT','ACTIVE','PENDING','RENEWAL_PENDING','TERMINATED','COMPLETED','CANCELLED','OVERDUE','ONHOLD']);
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
        Schema::dropIfExists('leases');
    }
};
