<?php

namespace HistoricalRecords;

use HistoricalRecords\Contracts\HistoryRepository as HistoryRepositoryContract;
use HistoricalRecords\Models\History;

class HistoryRepository implements HistoryRepositoryContract
{
    public function __construct(
        protected History $history,
    ) {
    }

    /**
     * Create history of user actions that affect the database.
     *
     * @param  mixed  $userId
     * @param  string  $table
     * @param  string  $keyword
     * @param  array|null  $payload
     */
    public function saveHistory($userId, string $table, string $keyword, ?array $payload = null)
    {
        //
    }
}
