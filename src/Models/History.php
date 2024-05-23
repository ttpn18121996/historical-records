<?php

namespace HistoricalRecords\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class History extends Model
{
    protected $fillable = ['user_id', 'table_name', 'keyword', 'payload', 'information', 'ip_address', 'created_at'];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        $provider = $this->provider ?: Config::get('auth.guards.api.provider');
 
        return $this->belongsTo(
            Config::get("auth.providers.{$provider}.model"),
        );
    }

    protected static function boot(): void
    {
        static::creating(function (History $history) {
            $history->created_at = Carbon::now();
        });
    }
}
