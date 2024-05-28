<?php

namespace HistoricalRecords\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class HistoryCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'historical-records:cleanup
        {--t|time= : Exclusion period when deleting (Ex: 7d|1m|1y|7days|1months|1years)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete history';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('histories')->where('created_at', '<', $this->getTime())->delete();

        $this->info('Histories have been removed.');

        return Command::SUCCESS;
    }

    /**
     * Get the storage time.
     */
    private function getTime(): Carbon
    {
        if ($this->option('time') && preg_match('/^([0-9]+)([d,m,y,days,months,years]+)$/i', $this->option('time'), $matches)) {
            switch ($matches[2]) {
                case 'd':
                case 'days':
                    return Carbon::now()->subDays($matches[1]);
                case 'm':
                case 'months':
                    return Carbon::now()->subMonths($matches[1]);
                case 'y':
                case 'years':
                    return Carbon::now()->subYears($matches[1]);
            }
        }

        return Carbon::now()->subDays(Config::get('historical-records.history_expires', 90));
    }
}
