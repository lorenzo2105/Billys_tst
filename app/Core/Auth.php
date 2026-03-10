<?php
declare(strict_types=1);

namespace App\Core;

class Auth
{
    public static function login(array $user): void
    {
        Session::set('user_id', $user['id']);
        Session::set('user_role', $user['role']);
        Session::set('user_name', $user['name']);
        Session::set('user_email', $user['email']);
        session_regenerate_id(true);
    }

    public static function logout(): void
    {
        Session::destroy();
    }

    public static function check(): bool
    {
        return Session::has('user_id');
    }

    public static function id(): ?int
    {
        $id = Session::get('user_id');
        return $id !== null ? (int)$id : null;
    }

    public static function role(): ?string
    {
        return Session::get('user_role');
    }

    public static function user(): array
    {
        return [
            'id'    => Session::get('user_id'),
            'role'  => Session::get('user_role'),
            'name'  => Session::get('user_name'),
            'email' => Session::get('user_email'),
        ];
    }

    public static function isAdmin(): bool
    {
        return self::role() === 'admin';
    }

    public static function isKitchen(): bool
    {
        return self::role() === 'kitchen';
    }

    public static function isClient(): bool
    {
        return self::role() === 'client';
    }

    public static function requireAuth(): void
    {
        if (!self::check()) {
            Session::flash('error', 'Veuillez vous connecter.');
            $baseUrl = $_ENV['APP_URL'] ?? '/Billys_tst/public';
            header('Location: ' . $baseUrl . '/login');
            exit;
        }
    }

    public static function requireRole(string ...$roles): void
    {
        self::requireAuth();
        if (!in_array(self::role(), $roles, true)) {
            http_response_code(403);
            die('Accès interdit.');
        }
    }
}
