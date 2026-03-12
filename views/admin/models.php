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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h2 style="margin: 0;">Lista de Modelos</h2>
            </div>
            <?php if (empty($models)): ?>
                <p>No hay modelos registrados.</p>
            <?php else: ?>
                <div class="table-responsive">
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
                </div>
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

        <?php if (!empty($models)): ?>
            <!-- Danger Zone -->
            <div class="danger-zone">
                <div class="danger-zone-header">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e94e3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    <h3>Zona de Peligro</h3>
                </div>
                <div class="danger-zone-content">
                    <div class="danger-zone-text">
                        <p>Las siguientes acciones son irreversibles. Por favor, procede con precaución.</p>
                    </div>
                    <form id="deleteAllForm" method="post" action="<?= BASE_URL ?>admin/models" style="display:none;">
                        <input type="hidden" name="deleteAll" value="1">
                    </form>
                    <button type="button" class="btn-danger-premium" onclick="confirmMassDeletion('todos los modelos', 'deleteAllForm')">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        Eliminar todos los modelos
                    </button>
                </div>
            </div>
        <?php endif; ?>
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