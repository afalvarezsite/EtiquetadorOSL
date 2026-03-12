<?php
// Variables injected by controller:
// $pcs: array of full PC records joined with components
// $cpus, $rams, $discs, $gpus: arrays for the selectors
// $successMessage: string
// $errorMessage: string
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de PCs</title>
    <!-- Use base URL for assets -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/sidebar.php'; ?>

    <div class="main-content">
        <h1>Gestión de PCs</h1>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($successMessage) ?>
            </div>
        <?php endif; ?>

        <!-- Añadir PC -->
        <div class="card">
            <h2>Añadir Nueva PC (No crea una etiqueta)</h2>
            <form method="post" action="<?= BASE_URL ?>admin/pc" id="pcForm">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <!-- Columna 1 -->
                    <div>
                        <div class="form-group">
                            <label>Tipo de Placa:</label>
                            <select name="board_type" required>
                                <option value="bios">BIOS</option>
                                <option value="uefi">UEFI</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Procesador (CPU):</label>
                            <select name="cpu_name" required>
                                <option value="">Seleccionar CPU</option>
                                <?php foreach ($cpus as $cpu): ?>
                                    <option value="<?= $cpu['id'] ?>">
                                        <?= htmlspecialchars($cpu['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Capacidad RAM:</label>
                            <select name="ram_capacity" required>
                                <option value="">Seleccionar Capacidad</option>
                                <?php foreach ($rams as $ram): ?>
                                    <option value="<?= $ram['id'] ?>">
                                        <?= (int) $ram['capacity'] ?>GB
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tipo RAM:</label>
                            <select name="ram_type" required>
                                <option value="DDR3">DDR3</option>
                                <option value="DDR4">DDR4</option>
                                <option value="DDR5">DDR5</option>
                            </select>
                        </div>
                    </div>

                    <!-- Columna 2 -->
                    <div>
                        <div class="form-group">
                            <label>Capacidad Disco:</label>
                            <select name="disc_capacity" required>
                                <option value="">Seleccionar Capacidad</option>
                                <?php foreach ($discs as $disc): ?>
                                    <option value="<?= $disc['id'] ?>">
                                        <?= (int) $disc['capacity'] ?>GB
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tipo Disco:</label>
                            <select name="disc_type" required>
                                <option value="HDD">HDD</option>
                                <option value="SSD">SSD</option>
                                <option value="NVMe">NVMe</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tarjeta Gráfica (GPU):</label>
                            <select name="gpu_name">
                                <option value="">Integrada (Ninguna)</option>
                                <?php foreach ($gpus as $gpu): ?>
                                    <option value="<?= $gpu['id'] ?>">
                                        <?= htmlspecialchars($gpu['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tipo GPU:</label>
                            <select name="gpu_type" required>
                                <option value="integrada">Integrada</option>
                                <option value="dedicada">Dedicada</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Conectividad -->
                <div class="form-group" style="margin-top: 15px;">
                    <label>Conectividad:</label>
                    <div style="display: flex; gap: 20px; align-items: center; margin-top: 5px;">
                        <label style="display: flex; align-items: center; gap: 5px; font-weight: normal;">
                            <input type="checkbox" name="wifi" value="true" style="width: auto;"> Wi-Fi
                        </label>
                        <input type="hidden" name="wifi" value="false">

                        <label style="display: flex; align-items: center; gap: 5px; font-weight: normal;">
                            <input type="checkbox" name="bluetooth" value="true" style="width: auto;"> Bluetooth
                        </label>
                        <input type="hidden" name="bluetooth" value="false">
                    </div>
                </div>

                <div class="form-group">
                    <label>Observaciones/Descripción:</label>
                    <textarea name="obser" rows="3" placeholder="Detalles adicionales..."></textarea>
                </div>

                <button type="submit" name="add" class="theme-button">Añadir PC</button>
            </form>
        </div>

        <!-- Listado -->
        <div class="card">
            <h2>Lista de PCs</h2>
            <?php if (empty($pcs)): ?>
                <p>No hay PCs registradas</p>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table class="tabla-modelo">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>SN</th>
                                <th>Placa</th>
                                <th>Procesador</th>
                                <th>RAM</th>
                                <th>Disco</th>
                                <th>Gráfica</th>
                                <th>Conectividad</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pcs as $pc): ?>
                                <tr>
                                    <td>
                                        <?= (int) $pc['id'] ?>
                                    </td>
                                    <td>
                                        <?php if ($pc['sn_prefix']): ?>
                                            <?= htmlspecialchars($pc['sn_prefix'] . "-" . str_pad($pc['sn_num'], 4, '0', STR_PAD_LEFT)) ?>
                                        <?php else: ?>
                                            <span style="color: var(--radio-red);">Sin SN</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars(strtoupper($pc['board_type'])) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($pc['cpu_name_text'] ?? 'Sin CPU') ?>
                                    </td>
                                    <td>
                                        <?= $pc['ram_capacity_text'] ? (int) $pc['ram_capacity_text'] . 'GB ' : '' ?>
                                        <?= htmlspecialchars($pc['ram_type'] ?? '') ?>
                                    </td>
                                    <td>
                                        <?= $pc['disc_capacity_text'] ? (int) $pc['disc_capacity_text'] . 'GB ' : '' ?>
                                        <?= htmlspecialchars($pc['disc_type'] ?? '') ?>
                                    </td>
                                    <td>
                                        <?php if ($pc['gpu_name_text']): ?>
                                            <?= htmlspecialchars($pc['gpu_name_text'] ?? '') ?>
                                            (<?= htmlspecialchars($pc['gpu_type'] ?? '') ?>)
                                        <?php else: ?>
                                            Integrada
                                        <?php endif; ?>
                                    </td>
                                    <td style="font-size: 13px;">
                                        WiFi: <input type="checkbox" <?= $pc['wifi'] === 'true' ? 'checked' : '' ?> disabled><br>
                                        BT: <input type="checkbox" <?= $pc['bluetooth'] === 'true' ? 'checked' : '' ?> disabled>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($pc['obser'] ?? '') ?>
                                    </td>
                                    <td class="actions">
                                        <a href="<?= BASE_URL ?>admin/pc?delete=<?= $pc['id'] ?>" class="btn btn-delete"
                                            onclick="return confirm('¿Estás seguro de eliminar esta PC?')">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="<?= BASE_URL ?>assets/js/script.js"></script>
    <script>
        // Handle messages timeout
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(el => el.style.display = 'none');
        }, 3000);

        // Checkbox workaround for POST
        document.getElementById('pcForm').addEventListener('submit', function () {
            const wifiCheckbox = document.querySelector('input[name="wifi"][type="checkbox"]');
            const btCheckbox = document.querySelector('input[name="bluetooth"][type="checkbox"]');

            if (wifiCheckbox.checked) {
                document.querySelector('input[name="wifi"][type="hidden"]').disabled = true;
            }
            if (btCheckbox.checked) {
                document.querySelector('input[name="bluetooth"][type="hidden"]').disabled = true;
            }
        });
    </script>
</body>

</html>