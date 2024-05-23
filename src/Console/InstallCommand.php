<?php

namespace HistoricalRecords\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'historical-records:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the HistoricalRecords resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('vendor:publish', ['--tag' => 'historical-records']);
    }
}
