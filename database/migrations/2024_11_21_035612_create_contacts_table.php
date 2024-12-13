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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('name');
            $table->string('email');
            $table->string('url')->nullable();
            $table->string('phone');
            $table->string('address');
            $table->string('remark')->nullable();
            $table->timestamps();
            // $table->date('created_at')->useCurrent();
            // $table->date('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('modified_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
