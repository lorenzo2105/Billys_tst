<?php
declare(strict_types=1);

namespace App\Core;

class App
{
    private Router $router;

    public function __construct()
    {
        Session::start();
        CSRF::generateToken();
        $this->router = new Router();
        $this->loadRoutes();
    }

    private function loadRoutes(): void
    {
        $router = $this->router;
        require BASE_PATH . '/routes/web.php';
    }

    public function run(): void
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];

        $route = $this->router->resolve($uri, $method);

        if ($route === null) {
            $this->notFound();
            return;
        }

        // Run middleware
        Middleware::handle($route['middleware']);

        // Instantiate controller and call method
        $controllerClass = 'App\\Controllers\\' . $route['controller'];

        if (!class_exists($controllerClass)) {
            $this->notFound();
            return;
        }

        $controller = new $controllerClass();
        $methodName = $route['method'];

        if (!method_exists($controller, $methodName)) {
            $this->notFound();
            return;
        }

        call_user_func_array([$controller, $methodName], $route['params']);
    }

    private function notFound(): void
    {
        http_response_code(404);
        $viewFile = BASE_PATH . '/app/Views/errors/404.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo '<h1>404 - Page non trouvée</h1>';
        }
    }
}
