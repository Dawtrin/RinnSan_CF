<?php

namespace Rinnsan\RinnSanWeb\Middleware;

class CorsMiddleware extends Middleware
{
    private $allowedOrigins;

    public function __construct($allowedOrigins = ['*'])
    {
        $this->allowedOrigins = $allowedOrigins;
    }

    /**
     * Handle CORS
     */
    public function handle($request)
    {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
        
        // Allow all origins if configured
        if (in_array('*', $this->allowedOrigins)) {
            header('Access-Control-Allow-Origin: *');
        } elseif (in_array($origin, $this->allowedOrigins)) {
            header("Access-Control-Allow-Origin: {$origin}");
        }
        
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept, Origin');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');
        
        // Handle preflight request
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
        
        return true;
    }
}

