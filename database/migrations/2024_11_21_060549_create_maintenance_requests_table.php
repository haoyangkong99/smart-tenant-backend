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
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('property_id');
            $table->bigInteger('unit_id');
            $table->bigInteger('maintainer_id');
            $table->string('issue_type');
            $table->enum('status',['PENDING','COMPLETED','INPROGRESS','CANCELLED']);
            $table->string('issue_attachment')->nullable();
            $table->date('created_at')->useCurrent();
            $table->date('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('modified_by')->nullable();
            $table->foreign('unit_id')->references('id')->on('property_units')->onDelete('cascade');
            $table->foreign('property_id')->references('id')->on('property')->onDelete('cascade');
            $table->foreign('maintainer_id')->references('id')->on('maintainers')->onDelete('cascade');

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
