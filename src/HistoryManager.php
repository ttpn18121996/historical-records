<?php

namespace HistoricalRecords;

use HistoricalRecords\Contracts\Historyable;
use HistoricalRecords\Models\History;
use Illuminate\Container\Container;
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
    public static function save(
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

        $model = static::model();
        
        $model->historyable_type = get_class($historyable);
        $model->historyable_id = $historyable->getKey();
        $model->feature = $feature;
        $model->keyword = $keyword;
        $model->payload = $payload;
        $model->information = json_encode($information);
        $model->ip_address = Request::ip();
        $model->save();

        return $model;
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
