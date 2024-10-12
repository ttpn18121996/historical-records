<?php

namespace HistoricalRecords\Contracts;

use HistoricalRecords\Models\History;
use Illuminate\Database\Eloquent\Model;

interface HistoryRepository
{
    /**
     * Create history of user actions that affect the database.
     */
    public function saveHistory(Model $historyable, string $feature, string $keyword, ?array $payload = null): ?History;

    /**
     * Clean up history.
     */
    public function cleanup(int $days = 90): void;
}
