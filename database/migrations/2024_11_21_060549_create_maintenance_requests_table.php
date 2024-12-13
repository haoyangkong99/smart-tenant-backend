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
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('property_id')->constrained('property')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('property_units')->onDelete('cascade');
            $table->foreignId('maintainer_id')->constrained('maintainers')->onDelete('cascade');
            $table->string('issue_type');
            $table->enum('status',['PENDING','COMPLETED','INPROGRESS','CANCELLED']);
            $table->string('issue_attachment')->nullable();
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
        Schema::dropIfExists('maintenance_requests');
    }
};
