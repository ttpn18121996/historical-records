<?php

namespace HistoricalRecords;

use HistoricalRecords\Contracts\HistoryRepository as HistoryRepositoryContract;
use HistoricalRecords\Models\History;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Request;

class HistoryRepository implements HistoryRepositoryContract
{
    public function __construct(
        protected History $history,
    ) {
    }

    /**
     * Create history of user actions that affect the database.
     *
     * @return \HistoricalRecords\Models\History
     */
    public function saveHistory(mixed $userId, string $tableName, string $keyword, ?array $payload = null)
    {
        $browser = Container::getInstance()->make('browser-detect')->detect();

        $device = match (true) {
            $browser->isMobile() => 'phone',
            $browser->isTablet() => 'tablet',
            $browser->isDesktop() => 'desktop',
            default => 'unknown',
        };

        $payload = isset($payload) ? json_encode($payload) : null;

        $information = [
            'device' => $device,
            'browser' => $browser->browserFamily(),
            'browser_version' => $browser->browserVersion(),
            'platform' => $browser->platformFamily(),
            'platform_version' => $browser->platformVersion(),
        ];

        return $this->history->create([
            'user_id' => $userId,
            'table' => $tableName,
            'keyword' => $keyword,
            'payload' => $payload,
            'information' => json_encode($information),
            'ip_address' => Request::ip(),
        ]);
    }
}
