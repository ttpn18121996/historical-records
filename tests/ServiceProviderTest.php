<?php

namespace HistoricalRecords\Tests;

use HistoricalRecords\Contracts\HistoryRepository as HistoryRepositoryContract;
use HistoricalRecords\HistoryRepository;

class ServiceProviderTest extends TestCase
{
    public function test_it_can_resolve_the_repository()
    {
        $expected = HistoryRepository::class;
        $actual = app(HistoryRepositoryContract::class);

        $this->assertInstanceOf($expected, $actual);
    }
}
