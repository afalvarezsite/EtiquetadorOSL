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
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $userModel = new User();
            $user = $userModel->findByUsernameOrEmail($username);

            // Valida con username o email
            // En el código original se usa password_verify
            if ($user && password_verify($password, $user['password'])) {
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
            $email = trim($_POST['email'] ?? '');
            $username = trim($_POST['username'] ?? ''); // Actually read username from POST
            $password = trim($_POST['password'] ?? '');

            $userModel = new User();
            if ($userModel->findByUsernameOrEmail($email)) {
                $this->render('auth/register', ['errorMessage' => 'El email ya está registrado']);
                return;
            }
            if ($userModel->findByUsernameOrEmail($username)) {
                $this->render('auth/register', ['errorMessage' => 'El nombre de usuario ya está registrado']);
                return;
            }

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            if ($userModel->create($username, $email, $passwordHash, 2)) { // 2 = User role
                $this->redirect('?registered=1');
            } else {
                $this->render('auth/register', ['errorMessage' => 'Error al registrar']);
            }
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        $this->redirect('');
    }

    // Pendientes por simplicidad: forgotPassword, resetPassword, verify2fa
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
