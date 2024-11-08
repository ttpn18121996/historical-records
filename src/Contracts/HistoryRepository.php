<?php

namespace HistoricalRecords\Contracts;

use HistoricalRecords\Models\History;

/**
 * @deprecated use \HistoricalRecords\HistoryManager instead of HistoryRepository
 */
interface HistoryRepository
{
    /**
     * Create history of user actions that affect the database.
     */
    public function saveHistory(
        Historyable $historyable,
        string $feature,
        string $keyword,
        ?array $payload = null,
    ): ?History;

    /**
     * Clean up history.
     */
    public function cleanup(int $days = 90): void;
}
