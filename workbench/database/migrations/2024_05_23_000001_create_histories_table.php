<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tableName = config('historical-records.table_name');

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();

            $morphKeyType = config('historical-records.morph_key_type', 'id');

            $morphKey = 'historyable';

            if ($morphKeyType === 'uuid') {
                $table->uuidMorphs($morphKey);
            } elseif ($morphKeyType === 'ulid') {
                $table->ulidMorphs($morphKey);
            } else {
                $table->numericMorphs($morphKey);
            }

            $table->string('feature')
                ->comment('The name of the feature');

            $table->string('keyword')
                ->comment('This keyword is used to display messages according to the key in the language file');

            $table->longText('payload')
                ->nullable();

            $table->longText('information')
                ->default(new Expression('(JSON_ARRAY())'))
                ->comment('device, browser, browser_version, platform');

            $table->ipAddress('ip_address')
                ->default('127.0.0.1');

            $table->timestamp('created_at')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = config('historical-records.table_name');

        Schema::dropIfExists($tableName);
    }
};
