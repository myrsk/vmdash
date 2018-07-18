<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vmdash:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup vmDash and install all required dependencies.';

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
        $envExample = base_path('.env.example');
        $env = base_path('.env');

        $this->line('Creating the environment file...');
        if (!file_exists($envExample))
        {
            $this->error('The environment file \'.env.example\' required for this installation does not exist.');
        }
        else
        {
            copy($envExample, $env);
            $this->info('File created successfully.' . PHP_EOL);
            $this->line('Configuring the encryption key...');
            $this->call('key:generate');
        }
    }
}
