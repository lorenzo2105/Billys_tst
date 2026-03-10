<?php
declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function view(string $viewName, array $data = []): void
    {
        $viewFile = BASE_PATH . '/app/Views/' . str_replace('.', '/', $viewName) . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View not found: {$viewName}");
        }

        // Extract data to make variables available in view
        extract($data);

        // Make helper functions available
        $csrf = CSRF::tokenField();
        $baseUrl = $_ENV['APP_URL'] ?? '/Billys_tst/public';

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        echo $content;
    }

    protected function layout(string $viewName, array $data = [], string $layout = 'layouts.main'): void
    {
        $viewFile = BASE_PATH . '/app/Views/' . str_replace('.', '/', $viewName) . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View not found: {$viewName}");
        }

        extract($data);

        $baseUrl = $_ENV['APP_URL'] ?? '/Billys_tst/public';
        $csrf = CSRF::tokenField();

        ob_start();
        require $viewFile;
        $pageContent = ob_get_clean();

        $layoutFile = BASE_PATH . '/app/Views/' . str_replace('.', '/', $layout) . '.php';
        if (!file_exists($layoutFile)) {
            throw new \RuntimeException("Layout not found: {$layout}");
        }

        require $layoutFile;
    }

    protected function json(mixed $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function redirect(string $url): void
    {
        $baseUrl = $_ENV['APP_URL'] ?? '/Billys_tst/public';
        header('Location: ' . $baseUrl . $url);
        exit;
    }

    protected function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header('Location: ' . $referer);
        exit;
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function sanitize(string $value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    protected function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleSet) {
            $ruleList = explode('|', $ruleSet);
            $value = $data[$field] ?? null;

            foreach ($ruleList as $rule) {
                $params = [];
                if (str_contains($rule, ':')) {
                    [$rule, $paramStr] = explode(':', $rule, 2);
                    $params = explode(',', $paramStr);
                }

                $error = match ($rule) {
                    'required' => empty($value) && $value !== '0' ? "Le champ {$field} est obligatoire." : null,
                    'email'    => $value && !filter_var($value, FILTER_VALIDATE_EMAIL) ? "Email invalide." : null,
                    'min'      => $value && strlen((string)$value) < (int)$params[0] ? "Minimum {$params[0]} caractères." : null,
                    'max'      => $value && strlen((string)$value) > (int)$params[0] ? "Maximum {$params[0]} caractères." : null,
                    'numeric'  => $value && !is_numeric($value) ? "Le champ {$field} doit être numérique." : null,
                    default    => null,
                };

                if ($error !== null) {
                    $errors[$field][] = $error;
                }
            }
        }

        return $errors;
    }
}
