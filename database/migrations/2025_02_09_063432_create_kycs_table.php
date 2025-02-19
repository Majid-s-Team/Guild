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
        Schema::create('kycs', function (Blueprint $table) {
            $table->id('kyc_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('person_name');
            $table->string('relation');
            $table->enum('kyc_type', ['NIC', 'Passport', 'Other']);
            $table->string('kyc_document')->nullable();
            $table->string('kyc_document2')->nullable();
            $table->enum('approve_status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kycs');
    }
};
