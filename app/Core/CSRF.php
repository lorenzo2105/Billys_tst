<?php
declare(strict_types=1);

namespace App\Core;

class CSRF
{
    public static function generateToken(): string
    {
        if (!Session::has('_csrf_token')) {
            Session::set('_csrf_token', bin2hex(random_bytes(32)));
        }
        return Session::get('_csrf_token');
    }

    public static function tokenField(): string
    {
        $token = self::generateToken();
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function verify(?string $token): bool
    {
        $sessionToken = Session::get('_csrf_token');
        if ($sessionToken === null || $token === null) {
            return false;
        }
        return hash_equals($sessionToken, $token);
    }

    public static function check(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check header first (preferred for AJAX), then POST form data
            $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['_csrf_token'] ?? null;

            if (!self::verify($token)) {
                http_response_code(403);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'CSRF token invalide.']);
                exit;
            }
        }
    }
}
