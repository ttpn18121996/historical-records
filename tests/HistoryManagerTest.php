<?php

namespace HistoricalRecords\Tests;

use HistoricalRecords\HistoryManager;
use Workbench\App\Models\History;
use Workbench\App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HistoryManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_save_history()
    {
        /** @var \Workbench\App\Models\User */
        $user = User::factory()->create();

        /** @var \Workbench\App\Models\User */
        $newUser = User::factory()->create();

        HistoryManager::useModel(History::class);
        $history = HistoryManager::save($user, 'users', 'create', $newUser->toArray());

        $this->assertEquals($history->feature, 'users');
        $this->assertEquals($history->keyword, 'create');
        $this->assertInstanceOf(History::class, $history);
    }
}
