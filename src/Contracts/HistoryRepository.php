<?php

namespace HistoricalRecords\Contracts;

use HistoricalRecords\Models\History;
use Illuminate\Database\Eloquent\Model;

interface HistoryRepository
{
    /**
     * Create history of user actions that affect the database.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $historyable
     * @param  string  $feature
     * @param  string  $keyword
     * @param  array|null  $payload
     * @return \HistoricalRecords\Models\History|null
     */
    public function saveHistory(Model $historyable, string $feature, string $keyword, ?array $payload = null): ?History;

    /**
     * Clean up history.
     *
     * @param  int  $days
     * @return void
     */
    public function cleanup(int $days = 90): void;
}
