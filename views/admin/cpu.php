<?php
// Variables injected by controller:
// $cpus: array of CPU records
// $successMessage: string
// $errorMessage: string
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de CPUs</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php include __DIR__ . '/sidebar.php'; ?>

    <div class="main-content">
        <h1>Gestión de CPUs</h1>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

        <!-- Añadir nueva CPU -->
        <div class="card">
            <h2>Añadir Nueva CPU</h2>
            <form method="post" action="<?= BASE_URL ?>admin/cpu">
                <div class="admin-form-row">
                    <input type="text" name="name" required placeholder="Nombre de la CPU">
                    <button type="submit" name="add" class="btn btn-primary">Añadir CPU</button>
                </div>
            </form>
        </div>

        <!-- Importar CPUs -->
        <div class="card">
            <h2>Importar CPUs (CSV/TXT)</h2>
            <p style="font-size: 0.9rem; margin-bottom: 15px; opacity: 0.8;">
                Sube un archivo .txt (un nombre por línea) o .csv (primera columna). Los duplicados se omitirán.
            </p>
            <form method="post" action="<?= BASE_URL ?>admin/cpu" enctype="multipart/form-data">
                <div class="drop-zone">
                    <span class="drop-zone__prompt">Arrastra tu archivo aquí o haz clic para subir</span>
                    <input type="file" name="import_file" class="drop-zone__input" accept=".csv,.txt" required>
                </div>
                <div class="admin-form-row">
                    <button type="submit" name="import" class="btn btn-secondary" style="width: 100%;">Importar Archivo</button>
                </div>
            </form>
        </div>

        <!-- Listado -->
        <div class="card">
            <form method="post" action="<?= BASE_URL ?>admin/cpu">
                <div class="admin-form-row">
                    <input type="search" name="pattern" placeholder="Buscar CPU..." autofocus>
                    <button type="submit" name="search" class="btn">Buscar</button>
                </div>
            </form>

            <div class="list-header-container" style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="margin: 0;">Lista de CPUs</h2>
            </div>
            <?php if (empty($cpus)): ?>
                <p>No hay CPUs registradas.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="tabla-modelo">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th style="text-align:right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cpus as $cpu): ?>
                                <tr>
                                    <td><?= (int) $cpu['id'] ?></td>
                                    <td><?= htmlspecialchars($cpu['name']) ?></td>
                                    <td class="actions">
                                        <button class="btn btn-edit"
                                            onclick="editCPU(<?= $cpu['id'] ?>, '<?= htmlspecialchars($cpu['name'], ENT_QUOTES) ?>')">Editar</button>
                                        <a href="<?= BASE_URL ?>admin/cpu?delete=<?= $cpu['id'] ?>" class="btn btn-delete"
                                            onclick="return confirm('¿Estás seguro de eliminar esta CPU?')">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Modal editar -->
        <div id="editModal" class="modal">
            <div class="form">
                <span class="close">&times;</span>
                <h2>Editar CPU</h2>
                <form method="post" action="<?= BASE_URL ?>admin/cpu">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_name">Nombre:</label>
                        <input type="text" id="edit_name" name="name" required>
                    </div>
                    <button type="submit" name="edit" class="theme-button">Guardar Cambios</button>
                </form>
            </div>
        </div>

        <?php if (!empty($cpus)): ?>
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
                    <form id="deleteAllForm" method="post" action="<?= BASE_URL ?>admin/cpu" style="display:none;">
                        <input type="hidden" name="deleteAll" value="1">
                    </form>
                    <button type="button" class="btn-danger-premium" onclick="confirmMassDeletion('todas las CPUs', 'deleteAllForm')">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        Eliminar todas las CPUs
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="<?= BASE_URL ?>assets/js/script.js"></script>
    <script>
        function editCPU(id, name) {
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