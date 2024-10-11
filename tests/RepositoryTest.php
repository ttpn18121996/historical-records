<?php

namespace HistoricalRecords\Tests;

use App\Models\User;
use HistoricalRecords\Contracts\HistoryRepository;
use HistoricalRecords\Models\History;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_save_history()
    {
        $user = User::factory()->create();
        $historyRepository = app(HistoryRepository::class);

        $newUser = User::factory()->create();

        $history = $historyRepository->saveHistory($user, 'users', 'create', $newUser->toArray());

        $this->assertEquals($history->feature, 'users');
        $this->assertEquals($history->keyword, 'create');
        $this->assertInstanceOf(History::class, $history);
    }
}
