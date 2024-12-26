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
        Schema::create('leases', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('property_id');
            $table->bigInteger('unit_id');
            $table->bigInteger('tenant_id');
            $table->string('lease_number')->unique();
            $table->timestamp('rent_start_date');
            $table->timestamp('rent_end_date');
            $table->double('rent_amount');
            $table->enum('rent_type', ['Daily', 'Weekly', 'Monthly', 'Yearly']);
            $table->integer('terms');
            $table->double('deposit_amount');
            $table->string('deposit_description')->nullable();
            $table->string('contract')->nullable();
            $table->enum('status',['DRAFT','ACTIVE','PENDING','RENEWAL_PENDING','TERMINATED','COMPLETED','CANCELLED','OVERDUE','ONHOLD']);
            $table->date('created_at')->useCurrent();
            $table->date('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('modified_by')->nullable();
            $table->foreign('unit_id')->references('id')->on('property_units')->onDelete('cascade');
            $table->foreign('property_id')->references('id')->on('property')->onDelete('cascade');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');

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
