<?php

namespace HistoricalRecords\Contracts;

use HistoricalRecords\Models\History;

interface HistoryRepository
{
    /**
     * Create history of user actions that affect the database.
     */
    public function saveHistory(
        Historyable $historyable,
        string $feature,
        string $keyword,
        ?array $payload = null
    ): ?History;

    /**
     * Clean up history.
     */
    public function cleanup(int $days = 90): void;
}
