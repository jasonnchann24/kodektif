<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class GenerateCoverage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:coverage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Coverage HTML + clover.xml';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $process = new Process(['phpdbg', '-d', 'memory_limit=1000M', '-qrr', 'vendor/phpunit/phpunit/phpunit', '--coverage-html', 'coverage-reports/']);
        $process->start();

        foreach ($process as $type => $data) {
            if ($process::OUT === $type) {
                echo $data;
            } else { // $process::ERR === $type
                echo $data;
            }
        }
    }
}
