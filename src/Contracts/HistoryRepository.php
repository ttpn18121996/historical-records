<?php

namespace HistoricalRecords\Contracts;

interface HistoryRepository
{
    /**
     * Create history of user actions that affect the database.
     *
     * @param  mixed  $userId
     * @param  string  $table
     * @param  string  $keyword
     * @param  array  $payload
     */
    public function saveHistory($userId, string $table, string $keyword, array $payload = []);
}
