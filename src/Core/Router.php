<?php

namespace Rinnsan\RinnSanWeb\Core;

class Router
{
    private $routes = [];

    public function get(string $path, $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, $handler): void
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete(string $path, $handler): void
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    private function addRoute(string $method, string $path, $handler, array $middleware = []): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'pattern' => $this->convertToPattern($path),
            'middleware' => $middleware
        ];
    }

    /**
     * Thêm route với middleware
     */
    public function getWithMiddleware(string $path, $handler, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function postWithMiddleware(string $path, $handler, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    public function putWithMiddleware(string $path, $handler, array $middleware = []): void
    {
        $this->addRoute('PUT', $path, $handler, $middleware);
    }

    public function deleteWithMiddleware(string $path, $handler, array $middleware = []): void
    {
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }

    private function convertToPattern(string $path): string
    {
        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $this->getCurrentPath();

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $path, $matches)) {
                array_shift($matches);
                
                // Execute middleware
                $request = ['path' => $path, 'method' => $method];
                if (!empty($route['middleware'])) {
                    foreach ($route['middleware'] as $middlewareClass) {
                        $middleware = new $middlewareClass();
                        if (!$middleware->handle($request)) {
                            return; // Middleware đã xử lý response
                        }
                    }
                }
                
                $this->executeHandler($route['handler'], $matches);
                return;
            }
        }

        $this->handle404();
    }

    private function getCurrentPath(): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        
        if ($basePath !== '/' && $basePath !== '\\') {
            $path = str_replace($basePath, '', $path);
        }
        
        return rtrim($path, '/') ?: '/';
    }

    private function executeHandler($handler, array $params = []): void
    {
        // Callable (closure or [object, method])
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
            return;
        }

        // Handler as array: [ControllerClass::class, 'method']
        if (is_array($handler) && count($handler) === 2) {
            $controller = $handler[0];
            $method = $handler[1];

            // If controller is provided as class name string, instantiate and call
            if (is_string($controller) && is_string($method)) {
                if (!class_exists($controller)) {
                    // Try to resolve short names (Api\ or Web\)
                    if (strpos($controller, 'Api\\') === 0) {
                        $controller = 'Rinnsan\\RinnSanWeb\\Controllers\\Api\\' . substr($controller, 4);
                    } elseif (strpos($controller, 'Web\\') === 0) {
                        $controller = 'Rinnsan\\RinnSanWeb\\Controllers\\Web\\' . substr($controller, 4);
                    }
                }

                if (!class_exists($controller)) {
                    throw new \Exception("Controller {$controller} not found");
                }

                $instance = new $controller();

                if (!method_exists($instance, $method)) {
                    throw new \Exception("Method {$method} not found in controller {$controller}");
                }

                call_user_func_array([$instance, $method], $params);
                return;
            }
        }

        // Handler as string 'Controller@method'
        if (is_string($handler)) {
            $this->callControllerMethod($handler, $params);
            return;
        }

        throw new \Exception("Invalid route handler");
    }

    private function callControllerMethod(string $handler, array $params): void
    {
        list($controller, $method) = explode('@', $handler);
        
        // Determine namespace based on controller path
        if (strpos($controller, 'Api\\') === 0) {
            $controller = 'Rinnsan\\RinnSanWeb\\Controllers\\Api\\' . substr($controller, 4);
        } elseif (strpos($controller, 'Web\\') === 0) {
            $controller = 'Rinnsan\\RinnSanWeb\\Controllers\\Web\\' . substr($controller, 4);
        } else {
            $controller = 'Rinnsan\\RinnSanWeb\\Controllers\\' . $controller;
        }

        if (!class_exists($controller)) {
            throw new \Exception("Controller {$controller} not found");
        }

        $controllerInstance = new $controller();

        if (!method_exists($controllerInstance, $method)) {
            throw new \Exception("Method {$method} not found in controller {$controller}");
        }

        call_user_func_array([$controllerInstance, $method], $params);
    }

    /**
     * Handle 404 Not Found
     */
    private function handle404(): void
    {
        http_response_code(404);
        
        // Check if it's an API request
        if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Endpoint không tồn tại',
                'data' => []
            ]);
        } else {
            echo "404 - Page not found";
        }
    }
}