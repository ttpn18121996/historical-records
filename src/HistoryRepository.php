<?php

namespace HistoricalRecords;

use HistoricalRecords\Contracts\Historyable;
use HistoricalRecords\Contracts\HistoryRepository as HistoryRepositoryContract;
use HistoricalRecords\Models\History;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

class HistoryRepository implements HistoryRepositoryContract
{
    public function __construct(
        protected History $history,
    ) {}

    /**
     * Create history of user actions that affect the database.
     */
    public function saveHistory(
        Historyable $historyable,
        string $feature,
        string $keyword,
        ?array $payload = null,
    ): ?History {
        $browser = Container::getInstance()->make('browser-detect')->detect();

        $device = match (true) {
            $browser->isMobile() => Config::get('historical-records.device_name.phone', 'phone'),
            $browser->isTablet() => Config::get('historical-records.device_name.tablet', 'tablet'),
            $browser->isDesktop() => Config::get('historical-records.device_name.desktop', 'desktop'),
            default => Config::get('historical-records.device_name.unknown', 'unknown'),
        };

        $payload = isset($payload) ? json_encode($payload) : null;

        $information = [
            'device' => $device,
            'browser' => $browser->browserFamily(),
            'browser_version' => $browser->browserVersion(),
            'platform' => $browser->platformFamily(),
            'platform_version' => $browser->platformVersion(),
        ];

        $this->history->historyable_type = get_class($historyable);
        $this->history->historyable_id = $historyable->getKey();
        $this->history->feature = $feature;
        $this->history->keyword = $keyword;
        $this->history->payload = $payload;
        $this->history->information = json_encode($information);
        $this->history->ip_address = Request::ip();
        $this->history->save();

        return $this->history;
    }

    /**
     * Clean up history.
     */
    public function cleanup(int $days = 90): void
    {
        Artisan::call('historical-records:cleanup', [
            '--time' => "{$days}d",
        ]);
    }
}
