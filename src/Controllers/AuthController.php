<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (isset($_SESSION['usuario_id'])) {
            $this->redirect('generator');
        }
        $this->render('auth/login');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrfToken(); // <-- PROTECCIÓN CSRF AÑADIDA

            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $userModel = new User();
            $user = $userModel->findByUsernameOrEmail($username);

            if ($user && password_verify($password, $user['password'])) {
                // <-- PROTECCIÓN CONTRA FIJACIÓN DE SESIÓN AÑADIDA
                session_regenerate_id(true);

                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['rol'] = $user['rol'];
                $this->redirect('generator');
            } else {
                $this->render('auth/login', ['errorMessage' => 'Credenciales inválidas.']);
            }
        }
    }

    public function showRegister()
    {
        if (isset($_SESSION['usuario_id'])) {
            $this->redirect('generator');
        }
        $this->render('auth/register');
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrfToken(); // <-- PROTECCIÓN CSRF AÑADIDA

            $email = trim($_POST['email'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $userModel = new User();

            // <-- MITIGACIÓN DE ENUMERACIÓN DE USUARIOS AÑADIDA
            $emailExists = $userModel->findByUsernameOrEmail($email);
            $userExists = $userModel->findByUsernameOrEmail($username);

            if ($emailExists || $userExists) {
                // Mensaje genérico para no revelar qué dato existe ya en la BD
                $this->render('auth/register', ['errorMessage' => 'Si los datos son válidos, el registro se completará.']);
                return;
            }

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            if ($userModel->create($username, $email, $passwordHash, 2)) {
                $this->redirect('?registered=1');
            } else {
                $this->render('auth/register', ['errorMessage' => 'Error al registrar']);
            }
        }
    }

    public function logout()
    {
        // <-- DESTRUCCIÓN SEGURA DE SESIÓN AÑADIDA
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
        $this->redirect('');
    }

    public function showForgotPassword()
    {
        $this->render('auth/login', ['errorMessage' => 'Funcionalidad pendiende de implementar']);
    }
    public function forgotPassword()
    {
    }
    public function showResetPassword()
    {
    }
    public function resetPassword()
    {
    }
    public function showVerify2fa()
    {
    }
    public function verify2fa()
    {
    }
}