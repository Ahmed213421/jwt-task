<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:project {name}'; // <-- اسم الأمر وحجته

    /**

     * The console command description.
    *
    * @var string
    */
    protected $description = 'Install and setup a new project with the given name';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        $this->info("Installing project: $name");

        // 1. Copy .env.example to .env
        if (!File::exists(base_path('.env'))) {
            File::copy(base_path('.env.example'), base_path('.env'));
            $this->info('.env file copied.');
        } else {
            $this->warn('.env file already exists. Skipping copy.');
        }

        // 2. Composer install
        $this->info('Running composer install...');
        $this->runProcess(['composer', 'install']);

        // 3. Generate key
        $this->info('Generating application key...');
        $this->call('key:generate');

        // 4. Migrate
        $this->info('Running migrations...');
        $this->call('migrate');

        // 5. Seed
        $this->info('Running seeders...');
        $this->call('db:seed');

        // 6. NPM install
        $this->info('Installing npm packages...');
        $this->runProcess(['npm', 'install']);

        // 7. NPM run build
        $this->info('Building frontend with Vite...');
        $this->runProcess(['npm', 'run', 'build']);

        $this->info("Project $name has been installed successfully!");
    }
}
