<?php
// Variables injected by controller:
// $models, $successMessage, $errorMessage
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Modelos</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/sidebar.php'; ?>

    <div class="main-content">
        <h1>Gestión de Modelos de Etiqueta</h1>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

        <div class="card">
            <h2>Añadir Nuevo Modelo</h2>
            <form method="post" action="<?= BASE_URL ?>admin/models">
                <div class="admin-form-row">
                    <input type="text" name="name" required placeholder="Nombre del Modelo">
                    <button type="submit" name="add" class="btn btn-primary">Añadir</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h2>Lista de Modelos</h2>
            <?php if (empty($models)): ?>
                <p>No hay modelos registrados.</p>
            <?php else: ?>
                <table class="tabla-modelo">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Modelo</th>
                            <th>Identificador</th>
                            <th style="text-align:right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($models as $model): ?>
                            <tr>
                                <td><?= (int) $model['id'] ?></td>
                                <td><?= htmlspecialchars($model['name']) ?></td>
                                <td><?= htmlspecialchars($model['model'] ?? '—') ?></td>
                                <td class="actions">
                                    <button class="btn btn-edit"
                                        onclick="editModel(<?= $model['id'] ?>, '<?= htmlspecialchars($model['name'], ENT_QUOTES) ?>')">Editar</button>
                                    <a href="<?= BASE_URL ?>admin/models?delete=<?= $model['id'] ?>" class="btn btn-delete"
                                        onclick="return confirm('¿Estás seguro de eliminar este Modelo?')">Eliminar</a>
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
                <h2>Editar Modelo</h2>
                <form method="post" action="<?= BASE_URL ?>admin/models">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_name">Nombre:</label>
                        <input type="text" id="edit_name" name="name" required>
                    </div>
                    <button type="submit" name="edit" class="theme-button">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>assets/js/script.js"></script>
    <script>
        function editModel(id, name) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
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