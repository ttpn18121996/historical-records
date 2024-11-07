<?php

namespace HistoricalRecords\Tests;

use HistoricalRecords\HistoryManager;

class CustomHistoryModel {}

class ServiceProviderTest extends TestCase
{
    public function test_it_can_resolve_the_repository()
    {
        HistoryManager::$modelName = CustomHistoryModel::class;

        $expected = CustomHistoryModel::class;
        $actual = HistoryManager::model();

        $this->assertInstanceOf($expected, $actual);
    }
}
