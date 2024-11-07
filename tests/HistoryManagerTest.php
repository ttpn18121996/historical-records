<?php

namespace HistoricalRecords\Tests;

use App\Models\User;
use HistoricalRecords\HistoryManager;
use HistoricalRecords\Models\History;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HistoryManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_save_history()
    {
        $user = User::factory()->create();

        $newUser = User::factory()->create();

        $history = HistoryManager::save($user, 'users', 'create', $newUser->toArray());

        $this->assertEquals($history->table_name, 'users');
        $this->assertEquals($history->keyword, 'create');
        $this->assertInstanceOf(History::class, $history);
    }
}
