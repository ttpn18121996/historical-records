<?php

namespace HistoricalRecords\Tests;

use App\Models\User;
use HistoricalRecords\HistoryManager;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_get_user_information()
    {
        /** @var \App\Models\User */
        $user = User::factory()->create();

        $history = HistoryManager::save($user, 'users', 'login');

        $this->assertEquals($user->id, $history->historyable_id);
    }

    public function test_it_can_get_payload_array()
    {
        /** @var \App\Models\User */
        $user = User::factory()->create();

        /** @var \App\Models\User */
        $newUser = User::factory()->create();

        $history = HistoryManager::save($user, 'users', 'create', $newUser->toArray());
        $payload = $history->payload_array;

        $this->assertTrue(is_array($payload));
    }

    public function test_it_can_get_information_array()
    {
        /** @var \App\Models\User */
        $user = User::factory()->create();

        /** @var \App\Models\User */
        $newUser = User::factory()->create();

        $history = HistoryManager::save($user, 'users', 'create', $newUser->toArray());
        $information = $history->information_array;

        $this->assertTrue(is_array($information));
    }

    public function test_it_can_get_info_for_trans()
    {
        /** @var \App\Models\User */
        $user = User::factory()->create(['name' => 'AAA']);

        /** @var \App\Models\User */
        $newUser = User::factory()->create();

        $history = HistoryManager::save($user, 'users', 'create', $newUser->toArray());
        $actionForTrans = $history->action_for_trans;
        $titleForTrans = $history->title_for_trans;

        $this->assertTrue($titleForTrans === 'historical.users.create.title');
        $this->assertTrue($actionForTrans === 'historical.users.create.action');
    }
}
