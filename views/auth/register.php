<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Etiquetador OSL</title>
    <link rel="icon" type="image/jpg" href="<?php echo $this->esc(BASE_URL); ?>assets/favicon.ico" />
    <link rel="stylesheet" href="<?php echo $this->esc(BASE_URL); ?>assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Tomorrow:wght@300;400;700&display=swap" rel="stylesheet">
</head>

<body class="dark">
    <div class="auth-card">
        <header class="auth-header">
            <h1>Registro</h1>
        </header>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger">
                <?php echo $this->esc($errorMessage); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success">
                <?php echo $this->esc($successMessage); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo $this->esc(BASE_URL); ?>register" method="POST" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?php echo $this->esc($csrf_token); ?>">
            <div class="form-group">
                <label for="username">Nombre de usuario</label>
                <input type="text" name="username" id="username" placeholder="Tu alias" required
                    value="<?php echo $this->esc($username ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email institucional</label>
                <input type="email" name="email" id="email" placeholder="usuario@ejemplo.com" required 
                    value="<?php echo $this->esc($email ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="Mínimo 8 caracteres" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmar contraseña</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Repite tu contraseña" required>
            </div>
            <button type="submit" class="auth-submit">Crear Cuenta</button>
        </form>

        <footer class="auth-footer">
            <p>¿Ya tienes una cuenta? <a href="<?php echo $this->esc(BASE_URL); ?>login">Inicia sesión</a></p>
        </footer>
    </div>
</body>

</html>