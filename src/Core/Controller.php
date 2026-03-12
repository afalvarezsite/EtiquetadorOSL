<?php

namespace App\Core;

abstract class Controller
{
    protected function render($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . '/../../views/' . $view . '.php';

        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("View {$view} not found!");
        }
    }

    protected function redirect($url)
    {
        header("Location: " . BASE_URL . ltrim($url, '/'));
        exit;
    }
}
