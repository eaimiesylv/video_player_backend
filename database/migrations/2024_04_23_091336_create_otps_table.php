<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id')->index()->nullable();
            $table->string('otp_code', 10)->nullable();
            $table->string('sms_status', 10)->nullable();
            $table->string('sent_via', 50)->nullable()->comment('email or phone number');
            $table->string('pending_email_or_phone_number', 150)->nullable();
            $table->timestamp('expiry_time');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
