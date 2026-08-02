<?php
namespace Core;

/**
 * Router — maps URL patterns to Controller@method pairs.
 * Supports named routes, {param} segments, and middleware groups.
 */
class Router
{
    private array $routes   = [];
    private array $named    = [];
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    // ── Route Registration ────────────────────────────────
    public function get(string $pattern, string $handler, string $name = ''): void
    {
        $this->addRoute('GET', $pattern, $handler, $name);
    }

    public function post(string $pattern, string $handler, string $name = ''): void
    {
        $this->addRoute('POST', $pattern, $handler, $name);
    }

    public function any(string $pattern, string $handler, string $name = ''): void
    {
        $this->addRoute('GET|POST', $pattern, $handler, $name);
    }

    private function addRoute(string $methods, string $pattern, string $handler, string $name): void
    {
        $route = [
            'methods' => explode('|', $methods),
            'pattern' => $this->compilePattern($pattern),
            'raw'     => $pattern,
            'handler' => $handler,
        ];

        $this->routes[] = $route;

        if ($name) {
            $this->named[$name] = $pattern;
        }
    }

    // ── Convert {param} pattern to regex ──────────────────
    private function compilePattern(string $pattern): string
    {
        $pattern = trim($pattern, '/');
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $pattern);
        return '#^' . $pattern . '$#';
    }

    // ── Dispatch ──────────────────────────────────────────
    public function dispatch(): void
    {
        $method = $this->request->method();
        $uri    = $this->request->uri();

        foreach ($this->routes as $route) {
            if (!in_array($method, $route['methods'])) {
                continue;
            }

            if (preg_match($route['pattern'], $uri, $matches)) {
                // Extract named params
                $params = array_filter(
                    $matches,
                    fn($key) => !is_int($key),
                    ARRAY_FILTER_USE_KEY
                );

                $this->callHandler($route['handler'], $params);
                return;
            }
        }

        // No route matched → 404
        $this->callHandler('ErrorController@show', ['code' => '404']);
    }

    // ── Resolve and call Controller@method ────────────────
    private function callHandler(string $handler, array $params = []): void
    {
        [$controllerName, $method] = explode('@', $handler);

        // Build full class name
        $namespace = str_contains($controllerName, '\\')
            ? 'Controllers\\'
            : 'Controllers\\';

        $class = $namespace . $controllerName;

        if (!class_exists($class)) {
            // Try without extra namespace prefix
            $class = 'Controllers\\' . $controllerName;
        }

        if (!class_exists($class)) {
            throw new \RuntimeException("Controller not found: $class");
        }

        $controller = new $class();

        if (!method_exists($controller, $method)) {
            throw new \RuntimeException("Method $method not found in $class");
        }

        $controller->$method($params);
    }

    // ── Named route URL generation ─────────────────────────
    public function route(string $name, array $params = []): string
    {
        if (!isset($this->named[$name])) {
            return '/';
        }

        $url = $this->named[$name];

        foreach ($params as $key => $value) {
            $url = str_replace('{' . $key . '}', $value, $url);
        }

        return $url;
    }
}
