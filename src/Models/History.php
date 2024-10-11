<?php

namespace HistoricalRecords\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

class History extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    /**
     * Get the parent commentable model (post or video).
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
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
    public function titleForTrans(): Attribute
    {
        return Attribute::make(
            get: fn () => 'historical.'.$this->feature.'.'.$this->keyword.'.title',
        );
    }

    /**
     * Get the action content to display for humans.
     */
    public function actionForTrans(): Attribute
    {
        return Attribute::make(
            get: fn () => 'historical.'.$this->feature.'.'.$this->keyword.'.action',
        );
    }

    /**
     * The "boot" method of the model.
     *
     * @return bool
     */
    public static function booted(): void
    {
        static::creating(function (History $history) {
            $history->created_at = Carbon::now();
        });
    }
}
