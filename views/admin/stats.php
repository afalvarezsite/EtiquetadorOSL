<?php
// No variables injected — all data is fetched via AJAX by Chart.js
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de PCs</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php include __DIR__ . '/sidebar.php'; ?>

    <div class="main-content">
        <h1>Estadísticas de PCs</h1>

        <div class="stats-grid">
            <!-- Gráfico CPUs -->
            <div class="card">
                <h2>Distribución de CPU</h2>
                <canvas id="cpuChart"></canvas>
            </div>

            <!-- Gráfico GPUs -->
            <div class="card">
                <h2>Distribución de GPU</h2>
                <canvas id="gpuChart"></canvas>
            </div>
        </div>

        <div class="stats-grid">
            <!-- Gráfico RAM -->
            <div class="card">
                <h2>Distribución de RAM</h2>
                <canvas id="ramChart"></canvas>
            </div>

            <!-- Gráfico Discos -->
            <div class="card">
                <h2>Distribución de Almacenamiento</h2>
                <canvas id="discChart"></canvas>
            </div>
        </div>

        <div class="stats-grid">
            <!-- Gráfico WiFi -->
            <div class="card">
                <h2>Distribución de WiFi</h2>
                <canvas id="wifiChart"></canvas>
            </div>

            <!-- Gráfico Bluetooth -->
            <div class="card">
                <h2>Distribución de Bluetooth</h2>
                <canvas id="bluetoothChart"></canvas>
            </div>
        </div>

        <!-- Seccion Configurador Personalizado -->
        <div class="card" style="margin-top: 30px;">
            <h2>Configurador de Gráfico Personalizado (WIP)</h2>
            <p>Selecciona una categoría y los elementos que quieras comparar.</p>

            <div class="config-section">
                <!-- Columna Izquierda: Configuracion -->
                <div>
                    <div class="form-group">
                        <label>Categoría:</label>
                        <select id="customCategory" class="form-control" style="width: 100%; margin-bottom: 15px;">
                            <option value="">Seleccionar...</option>
                            <option value="cpu">Procesador (CPU)</option>
                            <option value="gpu">Gráfica (GPU)</option>
                            <option value="ram">Memoria RAM</option>
                            <option value="disc">Disco</option>
                            <option value="wifi">WiFi</option>
                            <option value="bluetooth">Bluetooth</option>
                        </select>
                    </div>

                    <div id="itemsContainer" class="items-selector" style="display: none;">
                        <label style="display: block; margin-bottom: 10px; font-weight: bold;">Elementos:</label>
                        <div id="itemsList"></div>
                    </div>

                    <button id="generateBtn" class="theme-button"
                        style="margin-top: 15px; width: 100%; display: none;">Generar Gráfico</button>
                </div>

                <!-- Columna Derecha: Grafico -->
                <div class="card" style="margin: 0; background: rgba(0,0,0,0.05);">
                    <canvas id="customChart"></canvas>
                    <div id="noDataMsg" style="text-align: center; padding: 50px; color: grey;">
                        Selecciona elementos para ver la comparación
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>assets/js/script.js"></script>
    <script>
        const chartColors = [
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 99, 132, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(255, 159, 64, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(201, 203, 207, 0.7)',
            'rgba(100, 181, 246, 0.7)',
            'rgba(255, 138, 128, 0.7)',
            'rgba(128, 222, 234, 0.7)',
            'rgba(174, 213, 129, 0.7)',
            'rgba(255, 112, 67, 0.7)',
            'rgba(179, 136, 255, 0.7)',
            'rgba(255, 171, 145, 0.7)',
            'rgba(121, 134, 203, 0.7)'
        ];

        let currentCustomData = null;
        let myCustomChart = null;

        function createChart(elementId, title, stat, type = 'bar') {
            const url = '<?= BASE_URL ?>admin/stats/json?stat=' + stat;
            fetch(url)
                .then(r => r.json())
                .then(data => {
                    if (data.error) {
                        document.getElementById(elementId).parentElement.innerHTML +=
                            '<p style="color:var(--radio-red)">Error: ' + data.error + '</p>';
                        return;
                    }
                    const colors = data.labels.map((_, i) => chartColors[i % chartColors.length]);
                    const ctx = document.getElementById(elementId).getContext('2d');
                    new Chart(ctx, {
                        type: type,
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: title,
                                data: data.values,
                                backgroundColor: colors,
                                borderColor: colors.map(c => c.replace('0.7', '1')),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: type === 'bar' ? {
                                y: {
                                    beginAtZero: true,
                                    ticks: { color: 'var(--general-color)', stepSize: 1 },
                                    grid: { color: 'rgba(128,128,128,0.2)' }
                                },
                                x: {
                                    ticks: { color: 'var(--general-color)' },
                                    grid: { display: false }
                                }
                            } : {},
                            plugins: {
                                legend: {
                                    display: type === 'pie' || type === 'doughnut',
                                    labels: { color: 'var(--general-color)' }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: ctx => {
                                            const label = ctx.label || '';
                                            const value = ctx.parsed.y !== undefined ? ctx.parsed.y : ctx.parsed;
                                            return ' ' + label + ': ' + value + ' PC' + (value !== 1 ? 's' : '');
                                        }
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(err => console.error('Chart error for ' + stat, err));
        }

        // Logic for configurable chart
        const categorySelect = document.getElementById('customCategory');
        const itemsContainer = document.getElementById('itemsContainer');
        const itemsList = document.getElementById('itemsList');
        const generateBtn = document.getElementById('generateBtn');
        const noDataMsg = document.getElementById('noDataMsg');
        const customCanvas = document.getElementById('customChart');

        categorySelect.addEventListener('change', function () {
            const stat = this.value;
            if (!stat) {
                itemsContainer.style.display = 'none';
                generateBtn.style.display = 'none';
                return;
            }

            const url = '<?= BASE_URL ?>admin/stats/json?stat=' + stat;
            fetch(url)
                .then(r => r.json())
                .then(data => {
                    currentCustomData = data;
                    itemsList.innerHTML = '';
                    data.labels.forEach((label, index) => {
                        const div = document.createElement('label');
                        div.className = 'item-checkbox';
                        div.innerHTML = `<input type="checkbox" value="${index}" checked> <span>${label} (${data.values[index]})</span>`;
                        itemsList.appendChild(div);
                    });
                    itemsContainer.style.display = 'block';
                    generateBtn.style.display = 'block';
                });
        });

        generateBtn.addEventListener('click', function () {
            const checkedBoxes = Array.from(itemsList.querySelectorAll('input:checked'));
            if (checkedBoxes.length === 0) {
                alert('Selecciona al menos un elemento');
                return;
            }

            const indices = checkedBoxes.map(cb => parseInt(cb.value));
            const filteredLabels = indices.map(i => currentCustomData.labels[i]);
            const filteredValues = indices.map(i => currentCustomData.values[i]);
            const colors = filteredLabels.map((_, i) => chartColors[i % chartColors.length]);

            noDataMsg.style.display = 'none';
            customCanvas.style.display = 'block';

            if (myCustomChart) {
                myCustomChart.destroy();
            }

            const ctx = customCanvas.getContext('2d');
            const type = (categorySelect.value === 'wifi' || categorySelect.value === 'bluetooth') ? 'doughnut' : 'bar';

            myCustomChart = new Chart(ctx, {
                type: type,
                data: {
                    labels: filteredLabels,
                    datasets: [{
                        label: 'Comparativa Personalizada',
                        data: filteredValues,
                        backgroundColor: colors,
                        borderColor: colors.map(c => c.replace('0.7', '1')),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: type === 'bar' ? {
                        y: {
                            beginAtZero: true,
                            ticks: { color: 'var(--general-color)', stepSize: 1 },
                            grid: { color: 'rgba(128,128,128,0.2)' }
                        },
                        x: {
                            ticks: { color: 'var(--general-color)' },
                            grid: { display: false }
                        }
                    } : {},
                    plugins: {
                        legend: {
                            display: type !== 'bar',
                            labels: { color: 'var(--general-color)' }
                        }
                    }
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            createChart('cpuChart', 'PCs por CPU', 'cpu');
            createChart('gpuChart', 'PCs por GPU', 'gpu');
            createChart('ramChart', 'PCs por RAM', 'ram');
            createChart('discChart', 'PCs por Almacenamiento', 'disc');
            createChart('wifiChart', 'Conectividad WiFi', 'wifi', 'doughnut');
            createChart('bluetoothChart', 'Conectividad Bluetooth', 'bluetooth', 'doughnut');
        });
    </script>
</body>

</html>