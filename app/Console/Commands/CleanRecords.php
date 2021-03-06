<?php

namespace App\Console\Commands;

use App\Models\MicrobitData;
use Illuminate\Console\Command;

class CleanRecords extends Command
{
    const RECORD_LIFE_TIME = 21600 * 1000;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'microbit:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $milliseconds = round(microtime(true) * 1000);
        // remove 24hr old data
        MicrobitData::where('time', '<', ($milliseconds - self::RECORD_LIFE_TIME))->delete();
    }
}
