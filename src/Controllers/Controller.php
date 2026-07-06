<?php

namespace Rinnsan\RinnSanWeb\Controllers;

use Rinnsan\RinnSanWeb\Core\Application;
use Rinnsan\RinnSanWeb\Core\Database;
use Rinnsan\RinnSanWeb\Core\Config;
use Rinnsan\RinnSanWeb\Core\Session;

class Controller
{
    protected $db;
    protected $app;
    protected $config;
    protected $session;

    public function __construct()
    {
        $this->app = Application::getInstance();
        $this->db = Database::getInstance();
        $this->config = $this->app->getConfig();
        $this->session = $this->app->getSession();
    }

    protected function json($data, $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function view(string $view, array $data = []): void
    {
        $viewPath = __DIR__ . '/../../resources/views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            $this->json([
                'error' => 'View not found',
                'view' => $view
            ], 404);
            return;
        }

        extract($data);
        require $viewPath;
    }

    protected function redirect(string $url, $statusCode = 302): void
    {
        header("Location: $url", true, $statusCode);
        exit;
    }

    protected function input(string $key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            $rulesList = explode('|', $rule);

            foreach ($rulesList as $singleRule) {
                if ($singleRule === 'required' && empty($value)) {
                    $errors[$field][] = "The {$field} field is required.";
                }

                if ($singleRule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "The {$field} must be a valid email address.";
                }

                if (strpos($singleRule, 'min:') === 0) {
                    $min = (int) str_replace('min:', '', $singleRule);
                    if (strlen($value) < $min) {
                        $errors[$field][] = "The {$field} must be at least {$min} characters.";
                    }
                }
            }
        }

        return $errors;
    }
}