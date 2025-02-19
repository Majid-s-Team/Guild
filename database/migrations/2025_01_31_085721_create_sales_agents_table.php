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
        Schema::create('sales_agents', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->decimal('commission_amount', 10, 2)->nullable();
            $table->enum('commission_type', ['fixed', 'percentage'])->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('region')->nullable();
            $table->string('zcal_meeting_link')->nullable();
            $table->string('whatsApp_number')->nullable();
            $table->string('guild_email_address')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('iban_number')->nullable();
            $table->string('image')->nullable();
            $table->string('identification_document')->nullable();
            $table->string('agent_agreement')->nullable();
            $table->integer('user_id');
            $table->string('referral_link');
            $table->string('qr_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_agents');
    }
};
