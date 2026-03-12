<?php

namespace App\Middleware;

class AuthMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: " . BASE_URL);
            exit;
        }
    }
}
