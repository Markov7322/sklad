<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Filesystem\Filesystem;
use App\Models\User;

class InstallCommand extends Command
{
    protected $signature = 'app:install';

    protected $description = 'Interactive installer for the application';

    public function handle(): int
    {
        $this->info('Welcome to the Sklad installer');

        $appName = $this->ask('Site name', config('app.name', 'Laravel'));

        $dbConnection = $this->choice('Database connection', ['mysql', 'sqlite'], 'mysql');

        $dbHost = $this->ask('Database host', '127.0.0.1');
        $dbPort = $this->ask('Database port', '3306');
        $dbDatabase = $this->ask('Database name', 'sklad');
        $dbUsername = $this->ask('Database username', 'root');
        $dbPassword = $this->secret('Database password');

        $adminEmail = $this->ask('Admin email', 'admin@example.com');
        $adminPassword = $this->secret('Admin password');

        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            copy(base_path('.env.example'), $envPath);
        }

        $filesystem = new Filesystem();
        $env = collect($filesystem->lines($envPath))
            ->reject(function ($line) {
                return str_starts_with($line, 'APP_KEY=');
            })
            ->map(function ($line) use ($appName, $dbConnection, $dbHost, $dbPort, $dbDatabase, $dbUsername, $dbPassword) {
                $map = [
                    'APP_NAME' => $appName,
                    'DB_CONNECTION' => $dbConnection,
                    'DB_HOST' => $dbHost,
                    'DB_PORT' => $dbPort,
                    'DB_DATABASE' => $dbDatabase,
                    'DB_USERNAME' => $dbUsername,
                    'DB_PASSWORD' => $dbPassword,
                ];
                foreach ($map as $key => $value) {
                    if (str_starts_with($line, $key.'=')) {
                        return $key.'='.(strpos($value, ' ') !== false ? '"'.$value.'"' : $value);
                    }
                }
                return $line;
            });

        $filesystem->put($envPath, $env->implode(PHP_EOL));

        Artisan::call('key:generate', ['--force' => true]);
        Artisan::call('migrate', ['--force' => true]);

        User::query()->updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Admin',
                'password' => Hash::make($adminPassword),
                'role' => 'admin',
            ]
        );

        $this->info('Installation complete. You can now use the application.');

        return self::SUCCESS;
    }
}
