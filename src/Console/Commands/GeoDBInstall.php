<?php

namespace Peergum\GeoDB\Console\Commands;

use Illuminate\Console\Command;

class GeoDBInstall extends Command
{
    const VERSION = "v0.1-alpha";
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geodb:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        echo "GeoDB ".self::VERSION." Installed.\n";
    }
}
