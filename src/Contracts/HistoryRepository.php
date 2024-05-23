<?php

namespace HistoricalRecords\Contracts;

interface HistoryRepository
{
    /**
     * Create history of user actions that affect the database.
     *
     * @param  mixed  $userId
     * @param  string  $tableName
     * @param  string  $keyword
     * @param  array|null  $payload
     * @return \HistoricalRecords\Models\History
     */
    public function saveHistory($userId, string $tableName, string $keyword, ?array $payload = null);
}
