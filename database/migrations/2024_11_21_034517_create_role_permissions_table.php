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
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade'); // Foreign Key
            $table->string('permission');
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
        Schema::dropIfExists('role_permissions');
    }
};
