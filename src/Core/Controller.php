<?php

namespace App\Core;

abstract class Controller
{
    public function __construct()
    {
        // Asegurar que la sesión esté iniciada para manejar el token CSRF
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Generar token CSRF si no existe
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    protected function render($view, $data = [])
    {
        // Pasamos el token CSRF automáticamente a todas las vistas
        $data['csrf_token'] = $_SESSION['csrf_token'];

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

    /**
     * Valida el token CSRF recibido por POST
     * Detiene la ejecución si el token es inválido o no existe.
     */
    protected function verifyCsrfToken()
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'], $token)) {
            http_response_code(403);
            die("Error 403: Acción no autorizada (Token CSRF inválido).");
        }
    }

    /**
     * Helper para mitigar XSS Reflejado/Almacenado.
     * Úsalo en las vistas así: echo $this->esc($variable);
     */
    protected function esc($string)
    {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}