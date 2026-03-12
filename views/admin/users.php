<?php
// Variables injected by controller:
// $users, $roles, $successMessage, $errorMessage
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/sidebar.php'; ?>

    <div class="main-content">
        <h1>Gestión de Usuarios</h1>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

        <div class="card">
            <h2>Añadir Nuevo Usuario</h2>
            <form method="post" action="<?= BASE_URL ?>admin/users">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 12px; align-items: end;">
                    <div class="form-group" style="margin:0">
                        <label>Usuario:</label>
                        <input type="text" name="username" required>
                    </div>
                    <div class="form-group" style="margin:0">
                        <label>Contraseña:</label>
                        <input type="password" name="password" required>
                    </div>
                    <div class="form-group" style="margin:0">
                        <label>Email:</label>
                        <input type="email" name="email" required>
                    </div>
                    <button type="submit" name="add" class="btn btn-primary">Añadir</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h2>Lista de Usuarios</h2>
            <?php if (empty($users)): ?>
                <p>No hay usuarios registrados.</p>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table class="tabla-modelo">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th>Email</th>
                                <th>Creado</th>
                                <th style="text-align:right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= (int) $user['id'] ?></td>
                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                    <td><?= htmlspecialchars($user['rol_name'] ?? 'Usuario') ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= htmlspecialchars($user['created_at']) ?></td>
                                    <td class="actions">
                                        <button class="btn btn-edit"
                                            onclick="openEditPasswordModal(<?= (int) $user['id'] ?>, '<?= htmlspecialchars($user['username'], ENT_QUOTES) ?>')">Contraseña</button>
                                        <button class="btn btn-edit"
                                            onclick="openEditEmailModal(<?= (int) $user['id'] ?>, '<?= htmlspecialchars($user['username'], ENT_QUOTES) ?>', '<?= htmlspecialchars($user['email'], ENT_QUOTES) ?>')">Email</button>
                                        <button class="btn btn-edit"
                                            onclick="openEditRoleModal(<?= (int) $user['id'] ?>, '<?= htmlspecialchars($user['username'], ENT_QUOTES) ?>', <?= (int) $user['role_id'] ?>)">Rol</button>
                                        <a href="<?= BASE_URL ?>admin/users?delete=<?= (int) $user['id'] ?>"
                                            class="btn btn-delete"
                                            onclick="return confirm('¿Está seguro que desea eliminar este usuario?')">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Modal: Cambiar contraseña -->
        <div id="editPasswordModal" class="modal"
            style="display:none; position:fixed; z-index:1000; inset:0; background:rgba(0,0,0,0.5);">
            <div class="form" style="margin:10% auto; max-width:480px; border-radius:8px; position:relative;">
                <span class="close" data-modal="editPasswordModal"
                    style="position:absolute; top:12px; right:16px; font-size:24px; cursor:pointer; color:var(--general-color);">&times;</span>
                <h2>Cambiar Contraseña</h2>
                <form method="post" action="<?= BASE_URL ?>admin/users">
                    <input type="hidden" id="edit_password_user_id" name="userId">
                    <div class="form-group">
                        <label>Usuario:</label>
                        <input type="text" id="edit_password_username" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nueva Contraseña:</label>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit" name="newpass" class="theme-button">Actualizar Contraseña</button>
                </form>
            </div>
        </div>

        <!-- Modal: Cambiar email -->
        <div id="editEmailModal" class="modal"
            style="display:none; position:fixed; z-index:1000; inset:0; background:rgba(0,0,0,0.5);">
            <div class="form" style="margin:10% auto; max-width:480px; border-radius:8px; position:relative;">
                <span class="close" data-modal="editEmailModal"
                    style="position:absolute; top:12px; right:16px; font-size:24px; cursor:pointer; color:var(--general-color);">&times;</span>
                <h2>Cambiar Email</h2>
                <form method="post" action="<?= BASE_URL ?>admin/users">
                    <input type="hidden" id="edit_email_user_id" name="userId">
                    <div class="form-group">
                        <label>Usuario:</label>
                        <input type="text" id="edit_email_username" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nuevo Email:</label>
                        <input type="email" id="new_email" name="email" required>
                    </div>
                    <button type="submit" name="newemail" class="theme-button">Actualizar Email</button>
                </form>
            </div>
        </div>

        <!-- Modal: Cambiar rol -->
        <div id="editRoleModal" class="modal"
            style="display:none; position:fixed; z-index:1000; inset:0; background:rgba(0,0,0,0.5);">
            <div class="form" style="margin:10% auto; max-width:480px; border-radius:8px; position:relative;">
                <span class="close" data-modal="editRoleModal"
                    style="position:absolute; top:12px; right:16px; font-size:24px; cursor:pointer; color:var(--general-color);">&times;</span>
                <h2>Cambiar Rol</h2>
                <form method="post" action="<?= BASE_URL ?>admin/users">
                    <input type="hidden" id="edit_role_user_id" name="userId">
                    <div class="form-group">
                        <label>Usuario:</label>
                        <input type="text" id="edit_role_username" readonly>
                    </div>
                    <div class="form-group">
                        <label>Rol:</label>
                        <select name="role_id" id="edit_role_select" required>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id_rol'] ?>"><?= htmlspecialchars($role['nombre_rol']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" name="updaterole" class="theme-button">Actualizar Rol</button>
                </form>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>assets/js/script.js"></script>
    <script>
        function openEditPasswordModal(userId, username) {
            document.getElementById('edit_password_user_id').value = userId;
            document.getElementById('edit_password_username').value = username;
            document.getElementById('editPasswordModal').style.display = 'block';
        }
        function openEditEmailModal(userId, username, email) {
            document.getElementById('edit_email_user_id').value = userId;
            document.getElementById('edit_email_username').value = username;
            document.getElementById('new_email').value = email;
            document.getElementById('editEmailModal').style.display = 'block';
        }
        function openEditRoleModal(userId, username, roleId) {
            document.getElementById('edit_role_user_id').value = userId;
            document.getElementById('edit_role_username').value = username;
            document.getElementById('edit_role_select').value = roleId;
            document.getElementById('editRoleModal').style.display = 'block';
        }
        // Close buttons
        document.querySelectorAll('.close[data-modal]').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById(btn.dataset.modal).style.display = 'none';
            });
        });
        // Close on backdrop click
        window.addEventListener('click', e => {
            ['editPasswordModal', 'editEmailModal', 'editRoleModal'].forEach(id => {
                const el = document.getElementById(id);
                if (e.target === el) el.style.display = 'none';
            });
        });
        setTimeout(() => document.querySelectorAll('.alert').forEach(el => el.style.display = 'none'), 3000);
    </script>
</body>

</html>