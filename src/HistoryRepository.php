<?php

namespace HistoricalRecords;

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
    ) {
    }

    /**
     * Create history of user actions that affect the database.
     */
    public function saveHistory(mixed $userId, string $tableName, string $keyword, ?array $payload = null): mixed
    {
        if (is_null($userId)) {
            return null;
        }

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

        return $this->history->create([
            'user_id' => $this->resolveUser($userId)->id,
            'table_name' => $tableName,
            'keyword' => $keyword,
            'payload' => $payload,
            'information' => json_encode($information),
            'ip_address' => Request::ip(),
        ]);
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

    /**
     * Resolve the user.
     */
    protected function resolveUser($userId): mixed
    {
        $guard = Config::get('auth.defaults.guard');
        $provider = Config::get("auth.guards.{$guard}.provider");
        $userModel = Config::get("auth.providers.{$provider}.model");

        if ($userId instanceof $userModel) {
            return $userId;
        }

        return Container::getInstance()->make($userModel)->find($userId);
    }
}
