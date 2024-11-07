<?php

namespace HistoricalRecords\Tests;

use App\Models\History;
use App\Models\User;
use HistoricalRecords\HistoryManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

class CommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_be_clean_up_the_history()
    {
        $user = User::factory()->create();
        $history = HistoryManager::save($user->id, 'testing', 'test');
        $historyId = $history->id;
        $history->update(['created_at' => Carbon::now()->subDay()->subMinute()]);

        HistoryManager::cleanup(1);

        $this->assertNull(History::find($historyId));
    }

    /**
     * @dataProvider provider
     */
    public function test_it_can_be_clean_up_the_history_with_time_options(string $time, string $method, int $value)
    {
        $user = User::factory()->create();
        $history = HistoryManager::save($user->id, 'testing', 'test');
        $historyId = $history->id;
        $history->update(['created_at' => Carbon::now()->{$method}($value)->subMinute()]);

        $this->artisan('historical-records:cleanup', ['--time' => $time]);

        $this->assertNull(History::find($historyId));
    }

    public static function provider()
    {
        return [
            ['1m', 'subMonths', 1],
            ['1months', 'subMonths', 1],
            ['1y', 'subYears', 1],
            ['1years', 'subYears', 1],
            ['1invalid', 'subDays', 90],
        ];
    }
}
