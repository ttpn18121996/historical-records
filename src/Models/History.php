<?php

namespace HistoricalRecords\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class History extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'table_name', 'keyword', 'payload', 'information', 'ip_address', 'created_at'];

    public $timestamps = false;

    /**
     * Get the user that owns the history.
     */
    public function user(): BelongsTo
    {
        $provider = $this->provider ?: Config::get('auth.guards.api.provider');

        return $this->belongsTo(
            Config::get("auth.providers.{$provider}.model"),
        );
    }

    /**
     * Get the history's payload as an array.
     */
    public function payloadArray(): Attribute
    {
        return Attribute::make(
            get: fn () => (! is_null($this->payload) ? json_decode($this->payload, true) : []),
        );
    }

    /**
     * Get the history's information as an array.
     */
    public function informationArray(): Attribute
    {
        return Attribute::make(
            get: fn () => (! is_null($this->information) ? json_decode($this->information, true) : []),
        );
    }

    /**
     * Get the action content to display for humans.
     */
    public function actionForHuman(): Attribute
    {
        $translator = Container::getInstance()->make('translator');

        return Attribute::make(
            get: fn () => sprintf($translator->get(
                'history.'.$this->table_name.'.'.$this->keyword.'.action',
            ), $this->user->name),
        );
    }

    /**
     * The "boot" method of the model.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function (History $history) {
            $history->created_at = Carbon::now();
        });
    }
}
