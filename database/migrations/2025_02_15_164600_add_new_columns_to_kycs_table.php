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
        Schema::table('kycs', function (Blueprint $table) {
            $table->string('nationality')->nullable();
            $table->string('dob')->nullable();
            $table->string('email')->nullable();
            $table->integer('sales_agent_id')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->integer('reviewed_by')->nullable();
            $table->text('decline_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kycs', function (Blueprint $table) {
            $table->dropColumn([
                'nationality',
                'dob',
                'email',
                'sales_agent_id',
                'reviewed_at',
                'reviewed_by',
                'decline_reason'
            ]);
        });
    }
};
