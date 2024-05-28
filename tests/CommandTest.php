<?php

namespace HistoricalRecords\Tests;

use App\Models\History;
use App\Models\User;
use HistoricalRecords\Contracts\HistoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

class CommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_be_clean_up_the_history()
    {
        $historyRepository = app(HistoryRepository::class);
        $user = User::factory()->create();
        $history = $historyRepository->saveHistory($user->id, 'testing', 'test');
        $historyId = $history->id;
        $history->update(['created_at' => Carbon::now()->subDay()->subMinute()]);

        $this->artisan('historical-records:cleanup', ['--time' => '1d']);
        $this->assertNull(History::find($historyId));
    }
}
