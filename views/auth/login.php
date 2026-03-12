<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Panel de Acceso</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>

<body>
    <div class="main-container">
        <h1>Panel de acceso</h1>
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

        <form action="<?= BASE_URL ?>login" method="post">
            <div class="form-group">
                <label for="username">Usuario / Email:</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="login" class="btn">Iniciar Sesión</button>
        </form>
        <div style="text-align: center; margin-top: 15px;">
            <p>¿No tienes una cuenta? <a href="<?= BASE_URL ?>register">Regístrate aquí</a></p>
        </div>
        <div style="text-align: center; margin-top: 15px;">
            <p>¿Olvidaste tu contraseña? <a href="<?= BASE_URL ?>forgot-password">Recupérala aquí</a></p>
        </div>
    </div>
</body>

</html>