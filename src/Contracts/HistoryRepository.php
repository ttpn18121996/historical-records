<?php

namespace HistoricalRecords\Contracts;

interface HistoryRepository
{
    /**
     * Create history of user actions that affect the database.
     *
     * @return \HistoricalRecords\Models\History
     */
    public function saveHistory(mixed $userId, string $tableName, string $keyword, ?array $payload = null);
}
