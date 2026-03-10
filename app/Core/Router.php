<?php
declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];
    private array $middlewares = [];

    public function get(string $path, string $controller, string $method, array $middleware = []): self
    {
        return $this->addRoute('GET', $path, $controller, $method, $middleware);
    }

    public function post(string $path, string $controller, string $method, array $middleware = []): self
    {
        return $this->addRoute('POST', $path, $controller, $method, $middleware);
    }

    private function addRoute(string $httpMethod, string $path, string $controller, string $method, array $middleware): self
    {
        $this->routes[] = [
            'httpMethod'  => $httpMethod,
            'path'        => $path,
            'controller'  => $controller,
            'method'      => $method,
            'middleware'   => $middleware,
        ];
        return $this;
    }

    public function resolve(string $requestUri, string $requestMethod): ?array
    {
        $url = $this->parseUrl($requestUri);

        foreach ($this->routes as $route) {
            if ($route['httpMethod'] !== $requestMethod) {
                continue;
            }

            $params = $this->matchRoute($route['path'], $url);
            if ($params !== false) {
                return [
                    'controller' => $route['controller'],
                    'method'     => $route['method'],
                    'params'     => $params,
                    'middleware'  => $route['middleware'],
                ];
            }
        }

        return null;
    }

    private function parseUrl(string $uri): string
    {
        // Remove base path
        $basePath = '/Billys_tst/public';
        $url = parse_url($uri, PHP_URL_PATH) ?? '/';

        if (str_starts_with($url, $basePath)) {
            $url = substr($url, strlen($basePath));
        }

        $url = '/' . trim($url, '/');
        return $url;
    }

    private function matchRoute(string $routePath, string $requestPath): array|false
    {
        // Convert route pattern to regex
        // e.g., /menu/{id} -> /menu/(?P<id>[^/]+)
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $requestPath, $matches)) {
            // Extract named parameters only
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            return $params;
        }

        return false;
    }
}
