<?php

namespace Loopeer\QuickCms\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quickcms:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
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
        //$this->call('migrate', array('--env' => $this->option('env'), '--path' => 'loopeer/quickcms/database/migrations/' ));
        $this->call('db:seed', array('--class' => 'Loopeer\QuickCms\Seeds\InitBackendSeeder'));
    }
}
