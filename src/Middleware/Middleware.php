<?php

namespace Rinnsan\RinnSanWeb\Middleware;

class Middleware
{
    // Base middleware class
    // Các middleware khác có thể extend class này
    
    public function handle($request)
    {
        return true;
    }
}
