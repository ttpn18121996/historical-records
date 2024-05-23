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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('table_name')->comment('The name of the table in database');
            $table->string('keyword')->comment('This keyword is used to display messages according to the key in the language file');
            $table->longText('payload')->nullable();
            $table->longText('information')->default('{"device":"Unknown"}')->comment('device, device_family, device_model, platform, browser');
            $table->ipAddress('ip_address')->default('127.0.0.1');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
