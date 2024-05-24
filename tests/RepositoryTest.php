<?php

namespace HistoricalRecords\Tests;

use Orchestra\Testbench\Attributes\WithMigration;

#[WithMigration]
class RepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_save_history()
    {
        // $user = 
    }
}
