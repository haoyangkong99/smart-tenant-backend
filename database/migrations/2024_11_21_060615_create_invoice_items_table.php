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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->string('item_name');
            $table->double('amount');
            $table->string('description');
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
        Schema::dropIfExists('invoice_items');
    }
};
