<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Etiquetador OSL</title>
    <link rel="icon" type="image/jpg" href="<?= BASE_URL ?>assets/favicon.ico" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Tomorrow:wght@300;400;700&display=swap" rel="stylesheet">
</head>

<body class="dark"> <!-- Defaulting to dark for consistent branding, can be toggled via JS if needed -->
    <div class="auth-card">
        <header class="auth-header">
            <h1>Acceso</h1>
        </header>

        <?php if (isset($_GET['registered'])): ?>
            <div class="alert alert-success">¡Registro completado! Ya puedes iniciar sesión.</div>
        <?php endif; ?>
        <?php if (isset($successMessage) && !empty($successMessage)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($successMessage) ?>
            </div>
        <?php endif; ?>
        <?php if (isset($errorMessage) && !empty($errorMessage)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>login" method="post" class="auth-form">
            <div class="form-group">
                <label for="username">Usuario / Email</label>
                <input type="text" id="username" name="username" placeholder="Tu identificador" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" name="login" class="auth-submit">Entrar al Sistema</button>
        </form>

        <footer class="auth-footer">
            <p>¿No tienes una cuenta? <a href="<?= BASE_URL ?>register">Regístrate</a></p>
            <p style="margin-top: 10px;"><a href="<?= BASE_URL ?>forgot-password">¿Olvidaste tu contraseña?</a></p>
        </footer>
    </div>
</body>

</html>