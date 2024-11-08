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
        /** @var \App\Models\User */
        $user = User::factory()->create();

        /** @var \App\Models\User */
        $newUser = User::factory()->create();

        $history = HistoryManager::save($user, 'users', 'create', $newUser->toArray());

        $this->assertEquals($history->feature, 'users');
        $this->assertEquals($history->keyword, 'create');
        $this->assertInstanceOf(History::class, $history);
    }
}
