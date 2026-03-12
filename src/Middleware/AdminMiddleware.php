<?php

namespace App\Middleware;

class AdminMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['rol']) || strtolower($_SESSION['rol']) !== 'admin') {
            header("Location: " . BASE_URL . "generator");
            exit;
        }
    }
}
