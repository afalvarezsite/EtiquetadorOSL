<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario - Panel de Acceso</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>

<body>
    <div class="main-container">
        <h1>Crear una cuenta</h1>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>register" method="POST">
            <div class="form-group">
                <label for="username">Nombre de usuario:</label>
                <input type="text" name="username" id="username" required
                    value="<?= htmlspecialchars($username ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required value="<?= htmlspecialchars($email ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmar contraseña:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <button type="submit">Registrarse</button>
        </form>
        <div style="text-align: center; margin-top: 20px;">
            <p>¿Ya tienes una cuenta? <a href="<?= BASE_URL ?>login">Inicia sesión aquí</a></p>
        </div>
    </div>
</body>

</html>