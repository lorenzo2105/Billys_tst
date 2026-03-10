<?php
declare(strict_types=1);

namespace App\Core;

class EnvLoader
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function load(): void
    {
        // If .env doesn't exist, try to create it from .env.example
        if (!file_exists($this->path)) {
            $examplePath = str_replace('.env', '.env.example', $this->path);
            if (file_exists($examplePath)) {
                copy($examplePath, $this->path);
            } else {
                // Set default environment variables if no example file exists
                $this->setDefaultEnv();
                return;
            }
        }

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments
            if (str_starts_with($line, '#')) {
                continue;
            }

            if (str_contains($line, '=')) {
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Remove quotes
                $value = trim($value, '"\'');

                if (!array_key_exists($key, $_ENV)) {
                    $_ENV[$key] = $value;
                    putenv("$key=$value");
                }
            }
        }
    }

    private function setDefaultEnv(): void
    {
        $defaults = [
            'DB_HOST' => '127.0.0.1',
            'DB_PORT' => '3306',
            'DB_DATABASE' => 'billys_fastfood',
            'DB_USERNAME' => 'root',
            'DB_PASSWORD' => '',
            'APP_ENV' => 'development',
            'APP_DEBUG' => 'true',
            'APP_URL' => '/Billys_tst/public',
            'JWT_SECRET' => 'default-secret-key-change-in-production',
            'SESSION_NAME' => 'billys_session'
        ];

        foreach ($defaults as $key => $value) {
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}
