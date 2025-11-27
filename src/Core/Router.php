<?php

namespace Rinnsan\RinnSanWeb\Core;

class Router
{
    protected $routes = [];

    protected function add($method, $uri, $callback)
    {
        $paramNames = [];
        preg_match_all('/{([a-zA-Z0-9_]+)}/', $uri, $matches);
        $paramNames = $matches[1]; 

        $regexUri = preg_replace('/{([a-zA-Z0-9_]+)}/', '([^\/]+)', $uri);
        $regexPattern = '#^' . $regexUri . '$#'; 

        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'pattern' => $regexPattern,
            'callback' => $callback,
            'param_names' => $paramNames
        ];
    }

    public function get($uri, $callback)     { $this->add('GET', $uri, $callback); }
    public function post($uri, $callback)    { $this->add('POST', $uri, $callback); }
    public function put($uri, $callback)     { $this->add('PUT', $uri, $callback); }
    public function patch($uri, $callback)   { $this->add('PATCH', $uri, $callback); }
    public function delete($uri, $callback)  { $this->add('DELETE', $uri, $callback); }

    public function dispatch()
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if ($requestUri === '') $requestUri = '/';

        $matchedRoute = null;
        $params = [];
        $allowedMethods = [];

        foreach ($this->routes as $route) {
            if (preg_match($route['pattern'], $requestUri, $matches)) {
                $allowedMethods[] = $route['method'];
                
                if ($requestMethod === $route['method']) {
                    $matchedRoute = $route;
                    array_shift($matches); 
                    
                    if (!empty($route['param_names'])) {
                        $params = array_combine($route['param_names'], $matches);
                    }
                    break; 
                }
            }
        }

        if ($matchedRoute) {
            $callback = $matchedRoute['callback'];
            $this->callAction($callback[0], $callback[1], $params);
        } elseif (!empty($allowedMethods)) {
            http_response_code(405);
            echo "<h1>405 Method Not Allowed</h1>";
            echo "Phương thức {$requestMethod} không được hỗ trợ. Các phương thức được phép: " . implode(', ', $allowedMethods);
        } else {
            http_response_code(404);
            echo "<h1>404 Not Found</h1>";
            echo "Không tìm thấy trang cho: " . htmlspecialchars($requestUri);
        }
    }

    protected function callAction($controllerClass, $action, $params = [])
    {
        if (!class_exists($controllerClass)) {
            http_response_code(500);
            echo "<h1>500 Server Error</h1>Lỗi: Lớp Controller '{$controllerClass}' không tồn tại.";
            return;
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $action)) {
            http_response_code(500);
            echo "<h1>500 Server Error</h1>Lỗi: Phương thức '{$action}' không tồn tại trong Controller '{$controllerClass}'.";
            return;
        }
        
        $controller->$action($params);
    }
}