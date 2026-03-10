<?php
declare(strict_types=1);

namespace App\Core;

class Middleware
{
    public static function handle(array $middlewares): void
    {
        foreach ($middlewares as $middleware) {
            match ($middleware) {
                'auth'    => Auth::requireAuth(),
                'admin'   => Auth::requireRole('admin'),
                'kitchen' => Auth::requireRole('admin', 'kitchen'),
                'csrf'    => CSRF::check(),
                default   => null,
            };
        }
    }
}
