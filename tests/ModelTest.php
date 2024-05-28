<?php

namespace HistoricalRecords\Tests;

use App\Models\User;
use HistoricalRecords\Contracts\HistoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_get_user_information()
    {
        $user = User::factory()->create();
        $historyRepository = app(HistoryRepository::class);

        $history = $historyRepository->saveHistory($user, 'users', 'login');

        $this->assertEquals($user->id, $history->user_id);
    }

    public function test_it_can_get_payload_array()
    {
        $user = User::factory()->create();
        $historyRepository = app(HistoryRepository::class);

        $newUser = User::factory()->create();

        $history = $historyRepository->saveHistory($user, 'users', 'create', $newUser->toArray());
        $payload = $history->payload_array;

        $this->assertTrue(is_array($payload));
    }

    public function test_it_can_get_information_array()
    {
        $user = User::factory()->create();
        $historyRepository = app(HistoryRepository::class);

        $newUser = User::factory()->create();

        $history = $historyRepository->saveHistory($user, 'users', 'create', $newUser->toArray());
        $information = $history->information_array;

        $this->assertTrue(is_array($information));
    }

    public function test_it_can_get_info_for_trans()
    {
        $user = User::factory()->create(['name' => 'AAA']);
        $historyRepository = app(HistoryRepository::class);

        $newUser = User::factory()->create();

        $history = $historyRepository->saveHistory($user, 'users', 'create', $newUser->toArray());
        $actionForTrans = $history->action_for_trans;
        $titleForTrans = $history->title_for_trans;

        $this->assertTrue($titleForTrans === 'historical.users.create.title');
        $this->assertTrue($actionForTrans === 'historical.users.create.action');
    }
}
