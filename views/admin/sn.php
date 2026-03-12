<?php
// Variables injected by controller:
// $sns, $successMessage, $errorMessage
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Números de Serie</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/sidebar.php'; ?>

    <div class="main-content">
        <h1>Gestión de Números de Serie</h1>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>
        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <div class="card">
            <h2>Añadir Nuevo Prefijo SN</h2>
            <form method="post" action="<?= BASE_URL ?>admin/sn">
                <div class="admin-form-row">
                    <input type="text" name="prefix" required pattern="[A-Z]{3}" title="3 letras mayúsculas"
                        placeholder="Ej: PRU" maxlength="3" minlength="3" style="text-transform: uppercase;">
                    <button type="submit" name="add" class="btn btn-primary">Añadir Prefijo</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h2>Lista de Prefijos SN</h2>
            <?php if (empty($sns)): ?>
                <p>No hay prefijos SN registrados.</p>
            <?php else: ?>
                <table class="tabla-modelo">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Prefijo</th>
                            <th>Último Nº</th>
                            <th style="text-align:right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sns as $sn): ?>
                            <tr>
                                <td><?= (int) $sn['id'] ?></td>
                                <td><?= htmlspecialchars($sn['prefix']) ?></td>
                                <td><?= (int) $sn['num'] ?></td>
                                <td class="actions">
                                    <button class="btn btn-edit"
                                        onclick="openEditModal(<?= (int) $sn['id'] ?>, '<?= htmlspecialchars($sn['prefix'], ENT_QUOTES) ?>')">Editar</button>
                                    <a href="<?= BASE_URL ?>admin/sn?delete=<?= (int) $sn['id'] ?>" class="btn btn-delete"
                                        onclick="return confirm('¿Está seguro que desea eliminar este prefijo SN?')">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div id="editModal" class="modal"
            style="display:none; position:fixed; z-index:1000; inset:0; background:rgba(0,0,0,0.5);">
            <div class="form" style="margin:10% auto; max-width:480px; border-radius:8px; position:relative;">
                <span class="close"
                    style="position:absolute; top:12px; right:16px; font-size:24px; cursor:pointer; color:var(--general-color);">&times;</span>
                <h2>Editar Prefijo SN</h2>
                <form method="post" action="<?= BASE_URL ?>admin/sn">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_prefix">Prefijo:</label>
                        <input type="text" id="edit_prefix" name="prefix" required pattern="[A-Za-z0-9]{1,10}"
                            title="Máximo 10 caracteres alfanuméricos" style="text-transform: uppercase;">
                    </div>
                    <button type="submit" name="edit" class="theme-button">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>assets/js/script.js"></script>
    <script>
        function openEditModal(id, prefix) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_prefix').value = prefix;
            document.getElementById('editModal').style.display = 'block';
        }
        document.querySelector('#editModal .close').addEventListener('click', () => {
            document.getElementById('editModal').style.display = 'none';
        });
        window.addEventListener('click', e => {
            if (e.target === document.getElementById('editModal'))
                document.getElementById('editModal').style.display = 'none';
        });
        setTimeout(() => document.querySelectorAll('.alert').forEach(el => el.style.display = 'none'), 3000);
    </script>
</body>

</html>