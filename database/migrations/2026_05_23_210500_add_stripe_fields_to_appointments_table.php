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
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('stripe_session_id')->nullable()->after('status');
            $table->string('payment_status')->default('pending')->after('stripe_session_id');
            $table->decimal('amount_paid', 8, 2)->default(0.00)->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['stripe_session_id', 'payment_status', 'amount_paid']);
        });
    }
};
