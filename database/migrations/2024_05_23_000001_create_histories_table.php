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

            $morphKeyYype = config('historical-records.morph_key_type', 'id');

            if ($morphKeyYype === 'uuid') {
                $table->uuidMorphs('historyable');
            } elseif ($morphKeyYype === 'ulid') {
                $table->ulidMorphs('historyable');
            } else {
                $table->numericMorphs('historyable');
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
        Schema::dropIfExists('histories');
    }
};
