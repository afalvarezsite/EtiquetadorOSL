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
    <link rel="icon" type="image/jpg" href="<?php echo $this->esc(BASE_URL); ?>assets/favicon.ico" />
    <link rel="stylesheet" href="<?php echo $this->esc(BASE_URL); ?>assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php include __DIR__ . '/sidebar.php'; ?>

    <div class="main-content">
        <h1>Gestión de Números de Serie</h1>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"><?php echo $this->esc($successMessage); ?></div>
        <?php endif; ?>
        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-error"><?php echo $this->esc($errorMessage); ?></div>
        <?php endif; ?>

        <div class="card">
            <h2>Añadir Nuevo Prefijo SN</h2>
            <form method="post" action="<?php echo $this->esc(BASE_URL); ?>admin/sn">
                <input type="hidden" name="csrf_token" value="<?php echo $this->esc($csrf_token); ?>">
                <div class="admin-form-row">
                    <input type="text" name="prefix" required pattern="[A-Z]{3}" title="3 letras mayúsculas"
                        placeholder="Ej: PRU" maxlength="3" minlength="3" style="text-transform: uppercase;">
                    <button type="submit" name="add" class="btn btn-primary">Añadir Prefijo</button>
                </div>
            </form>
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h2 style="margin: 0;">Lista de Prefijos SN</h2>
            </div>
            <?php if (empty($sns)): ?>
                <p>No hay prefijos SN registrados.</p>
            <?php else: ?>
                <div class="table-responsive">
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
                                    <td><?php echo $this->esc((int) $sn['id']); ?></td>
                                    <td><?php echo $this->esc($sn['prefix']); ?></td>
                                    <td><?php echo $this->esc((int) $sn['num']); ?></td>
                                    <td class="actions">
                                        <button class="btn btn-edit"
                                            onclick="openEditModal(<?php echo $this->esc((int) $sn['id']); ?>, '<?php echo $this->esc($sn['prefix']); ?>')">Editar</button>
                                        <a href="<?php echo $this->esc(BASE_URL); ?>admin/sn?delete=<?php echo $this->esc((int) $sn['id']); ?>" class="btn btn-delete"
                                            onclick="return confirm('¿Está seguro que desea eliminar este prefijo SN?')">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <div id="editModal" class="modal">
            <div class="form">
                <span class="close">&times;</span>
                <h2>Editar Prefijo SN</h2>
                <form method="post" action="<?php echo $this->esc(BASE_URL); ?>admin/sn">
                    <input type="hidden" name="csrf_token" value="<?php echo $this->esc($csrf_token); ?>">
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

        <?php if (!empty($sns)): ?>
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
                    <form id="deleteAllForm" method="post" action="<?php echo $this->esc(BASE_URL); ?>admin/sn" style="display:none;">
                        <input type="hidden" name="csrf_token" value="<?php echo $this->esc($csrf_token); ?>">
                        <input type="hidden" name="deleteAll" value="1">
                    </form>
                    <button type="button" class="btn-danger-premium" onclick="confirmMassDeletion('todos los prefijos SN', 'deleteAllForm')">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        Eliminar todos los prefijos SN
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="<?php echo $this->esc(BASE_URL); ?>assets/js/script.js"></script>
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