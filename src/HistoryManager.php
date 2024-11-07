<?php

namespace HistoricalRecords;

use HistoricalRecords\Models\History;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

class HistoryManager
{
    public static string $modelName;

    public static function model()
    {
        return Container::getInstance()->make(static::$modelName ?? History::class);
    }

    /**
     * Create history of user actions that affect the database.
     */
    public static function save(mixed $userId, string $tableName, string $keyword, ?array $payload = null): mixed
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

        $userId = $userId instanceof Model ? $userId->getKey() : $userId;

        return static::model()->create([
            'user_id' => $userId,
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
    public static function cleanup(int $days = 90): void
    {
        Artisan::call('historical-records:cleanup', [
            '--time' => "{$days}d",
        ]);
    }
}
