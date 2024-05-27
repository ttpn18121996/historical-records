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

        $this->assertEquals($history->table_name, 'users');
        $this->assertEquals($history->keyword, 'create');
        $this->assertInstanceOf(History::class, $history);
    }

    public function it_can_resolve_the_user_by_id()
    {
        $user = User::factory()->create();
        $historyRepository = app(HistoryRepository::class);
        $actual = $historyRepository->resolveUser($user->id);

        $this->assertInstanceOf(User::class, $actual);
        $this->assertEquals($user->id, $actual->id);
    }
}
