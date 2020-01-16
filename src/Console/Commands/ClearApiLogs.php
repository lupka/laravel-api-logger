<?php

namespace Lupka\ApiLogger\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use Lupka\ApiLogger\Models\ApiLog;

class ClearApiLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-logger:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes API logs based on config setting.';

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
        $this->line('Clearing API logs...');
        $count = ApiLog::where('created_at', '<', Carbon::now()->subDays(config('api_logger.log_expiry')))->delete();
        $this->line($count.' logs deleted');
    }
}
