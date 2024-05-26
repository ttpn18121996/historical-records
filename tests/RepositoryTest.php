<?php

namespace HistoricalRecords\Tests;

use App\Models\User;
use HistoricalRecords\Contracts\HistoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Attributes\WithMigration;
use HistoricalRecords\Models\History;

class RepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_save_history()
    {
        $user = User::factory()->create();
        $historyRepository = app(HistoryRepository::class);

        $newUser = User::factory()->create();

        $history = $historyRepository->saveHistory($user, 'users', 'create', $newUser->toArray());

        $this->assertEquals($history->table_name, 'users');
        $this->assertEquals($history->keyword, 'create');
        $this->assertInstanceOf(History::class, $history);
    }
}
