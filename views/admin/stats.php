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
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(420px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stats-grid canvas {
            max-height: 300px;
            width: 100% !important;
        }
    </style>
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

        function createChart(elementId, title, stat) {
            const url = '<?= BASE_URL ?>admin/stats/json?stat=' + stat;
            fetch(url)
                .then(r => r.json())
                .then(data => {
                    if (data.error) {
                        document.getElementById(elementId).parentElement.innerHTML +=
                            '<p style="color:var(--radio-red)">Error: ' + data.error + '</p>';
                        return;
                    }
                    // Pad colors array to match data length
                    const colors = data.labels.map((_, i) => chartColors[i % chartColors.length]);
                    const ctx = document.getElementById(elementId).getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
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
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { color: 'var(--general-color)', stepSize: 1 },
                                    grid: { color: 'rgba(128,128,128,0.2)' }
                                },
                                x: {
                                    ticks: { color: 'var(--general-color)' },
                                    grid: { display: false }
                                }
                            },
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: ctx => ' ' + ctx.parsed.y + ' PC' + (ctx.parsed.y !== 1 ? 's' : '')
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(err => console.error('Chart error for ' + stat, err));
        }

        document.addEventListener('DOMContentLoaded', function () {
            createChart('cpuChart', 'PCs por CPU', 'cpu');
            createChart('gpuChart', 'PCs por GPU', 'gpu');
            createChart('ramChart', 'PCs por RAM', 'ram');
            createChart('discChart', 'PCs por Almacenamiento', 'disc');
        });
    </script>
</body>

</html>