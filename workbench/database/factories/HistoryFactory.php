<?php

namespace Database\Factories;

use App\Models\History;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistoryFactory extends Factory
{
    protected $model = History::class;

    public function definition(): array
    {
        return [
            'table_name' => 'users',
            'keyword' => 'create',
            'payload' => json_encode([
                'id' => random_int(1, 100),
                'name' => $this->faker->name(),
                'email' => $this->faker->safeEmail(),
            ]),
            'information' => json_encode([
                'device' => 'unknown',
                'browser' => 'Firefox',
                'browser_version' => '0.9',
                'platform' => 'Windows',
                'platform_version' => '10',
            ]),
            'ip_address' => '127.0.0.1',
            'created_at' => now(),
        ];
    }
}
