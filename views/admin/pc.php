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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                <div class="form-grid">
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
                    <div style="display: flex; gap: 30px; align-items: center; margin-top: 10px; flex-wrap: wrap;">
                        <!-- Wi-Fi -->
                        <div class="cntr" style="display: flex; align-items: center; gap: 10px;">
                            <input type="checkbox" id="wifi_cbx" name="wifi" value="true" class="hidden-xs-up cbx-input">
                            <label for="wifi_cbx" class="cbx"></label>
                            <span class="lbl">Wi-Fi</span>
                        </div>
                        <input type="hidden" name="wifi" value="false">

                        <!-- Bluetooth -->
                        <div class="cntr" style="display: flex; align-items: center; gap: 10px;">
                            <input type="checkbox" id="blue_cbx" name="bluetooth" value="true" class="hidden-xs-up cbx-input">
                            <label for="blue_cbx" class="cbx"></label>
                            <span class="lbl">Bluetooth</span>
                        </div>
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
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h2 style="margin: 0;">Lista de PCs</h2>
            </div>
            <?php if (empty($pcs)): ?>
                <p>No hay PCs registradas</p>
            <?php else: ?>
                <div class="table-responsive">
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

        <?php if (!empty($pcs)): ?>
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
                    <form id="deleteAllForm" method="post" action="<?= BASE_URL ?>admin/pc" style="display:none;">
                        <input type="hidden" name="deleteAll" value="1">
                    </form>
                    <button type="button" class="btn-danger-premium" onclick="confirmMassDeletion('todas las PCs', 'deleteAllForm')">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        Eliminar todas las PCs
                    </button>
                </div>
            </div>
        <?php endif; ?>
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