<?php

namespace Rinnsan\RinnSanWeb\Middleware;

use Rinnsan\RinnSanWeb\Validation\Validator;
use Rinnsan\RinnSanWeb\Helpers\RequestHelper;

class ValidationMiddleware extends Middleware
{
    private $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Validate request data
     */
    public function handle($request)
    {
        $validator = new Validator();
        $data = RequestHelper::input();
        
        if (!$validator->validate($data, $this->rules)) {
            http_response_code(422);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->getErrors()
            ]);
            exit;
        }
        
        return true;
    }
}

