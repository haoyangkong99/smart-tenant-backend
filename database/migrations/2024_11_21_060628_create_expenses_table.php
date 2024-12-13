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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('property_id')->constrained('property')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('property_units')->onDelete('cascade');
            $table->string('receipt_number')->unique();
            $table->timestamp('receipt_date');
            $table->string('expense_type');
            $table->double('total_amount');
            $table->string('remarks')->nullable();
            $table->string('attachment');
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
        Schema::dropIfExists('expenses');
    }
};
