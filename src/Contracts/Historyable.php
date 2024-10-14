<?php

namespace HistoricalRecords\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Historyable
{
    public function histories(): MorphMany;

    public function getKey();
}
