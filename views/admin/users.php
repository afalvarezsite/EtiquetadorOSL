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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); align-items: end;">
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
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h2 style="margin: 0;">Lista de Usuarios</h2>
            </div>
            <?php if (empty($users)): ?>
                <p>No hay usuarios registrados.</p>
            <?php else: ?>
                <div class="table-responsive">
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
        <div id="editPasswordModal" class="modal">
            <div class="form">
                <span class="close" data-modal="editPasswordModal">&times;</span>
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
        <div id="editEmailModal" class="modal">
            <div class="form">
                <span class="close" data-modal="editEmailModal">&times;</span>
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
        <div id="editRoleModal" class="modal">
            <div class="form">
                <span class="close" data-modal="editRoleModal">&times;</span>
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

        <?php if (count($users) > 1): ?>
            <!-- Danger Zone -->
            <div class="danger-zone">
                <div class="danger-zone-header">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e94e3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    <h3>Zona de Peligro</h3>
                </div>
                <div class="danger-zone-content">
                    <div class="danger-zone-text">
                        <p>Las siguientes acciones son irreversibles. No podrás borrar tu propia cuenta administrativa.</p>
                    </div>
                    <form id="deleteAllForm" method="post" action="<?= BASE_URL ?>admin/users" style="display:none;">
                        <input type="hidden" name="deleteAll" value="1">
                    </form>
                    <button type="button" class="btn-danger-premium" onclick="confirmMassDeletion('todos los usuarios (excepto tú)', 'deleteAllForm')">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        Eliminar todos los usuarios
                    </button>
                </div>
            </div>
        <?php endif; ?>
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