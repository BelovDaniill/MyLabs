<?php
declare(strict_types=1);

/** Minimal method/path router. */
class Router
{
    private array $routes = [];

    /** Registers a route handler. */
    public function get(string $path, callable $handler): void { $this->routes['GET'][$path] = $handler; }
    /** Registers a route handler. */
    public function post(string $path, callable $handler): void { $this->routes['POST'][$path] = $handler; }

    /** Executes the matching route or displays a 404 page. */
    public function dispatch(string $method, string $path): void
    {
        $handler = $this->routes[$method][$path] ?? null;
        if ($handler) {
            $handler();
            return;
        }
        http_response_code(404);
        render('errors/404', ['title' => 'Страница не найдена']);
    }
}
