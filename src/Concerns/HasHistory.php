<?php

namespace HistoricalRecords\Concerns;

use App\Models\History;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasHistory
{
    /**
     * Get all of the model's histories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function histories(): MorphMany
    {
        return $this->morphMany(History::class, 'historyable');
    }
}
