<?php

namespace HistoricalRecords\Contracts;

use HistoricalRecords\Models\History;

interface HistoryRepository
{
    /**
     * Create history of user actions that affect the database.
     */
    public function saveHistory(mixed $userId, string $tableName, string $keyword, ?array $payload = null): mixed;

    /**
     * Clean up history.
     */
    public function cleanup(int $days = 90): void;
}
