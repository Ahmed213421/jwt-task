<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class InstallProject extends Command
{
    protected $signature = 'install:project {name}';
    protected $description = 'Install and setup a new project with the given name';

    public function handle()
    {
        $name = $this->argument('name');

        $this->info("Installing project: $name");

        // Step 1: Copy .env file
        if (!File::exists(base_path('.env'))) {
            File::copy(base_path('.env.example'), base_path('.env'));
            $this->info('.env file copied.');
        } else {
            $this->warn('.env file already exists. Skipping copy.');
        }

        // Step 2: Composer install
        $this->info('Running composer install...');
        $this->runProcess(['composer', 'install']);

        // Step 3: Generate key
        $this->info('Generating application key...');
        $this->call('key:generate');

        // Step 4: Migrate
        $this->info('Running migrations...');
        $this->call('migrate');

        // Step 5: Seed
        $this->info('Running seeders...');
        $this->call('db:seed');

        // Step 6: npm install
        $this->info('Installing npm packages...');
        $this->runProcess(['npm', 'install']);

        // Step 7: npm run dev (for development environment)
        $this->info('Running Vite dev server...');
        $this->runProcess(['npm', 'run', 'build']);

        // Step 8: Start the queue worker
        $this->info('Starting queue:work...');
        $this->runProcess(['php', 'artisan', 'queue:work']);

        $this->info("✅ Project $name has been installed and is now running.");
    }

    protected function runProcess(array $command)
    {
        $process = new Process($command);
        $process->setTimeout(null); // No timeout
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        if (!$process->isSuccessful()) {
            $this->error("Command failed: " . implode(' ', $command));
        }
    }
}
