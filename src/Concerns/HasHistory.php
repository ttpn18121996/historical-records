<?php

namespace HistoricalRecords\Concerns;

use App\Models\History;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasHistory
{
    /**
     * Get all of the model's histories.
     */
    public function histories(): MorphMany
    {
        return $this->morphMany(History::class, 'historyable');
    }
}
