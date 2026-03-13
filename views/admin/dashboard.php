<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Panel de Administración</title>
    <link rel="icon" type="image/jpg" href="<?php echo $this->esc(BASE_URL); ?>assets/favicon.ico" />
    <link rel="stylesheet" href="<?php echo $this->esc(BASE_URL); ?>assets/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="main-content">
        <h1>Dashboard</h1>
        <!-- Linea 1 -->
        <div class="stats-container">
            <div class="stat-card">
                <h3>CPUs Registradas</h3>
                <p class="counter" data-target="<?php echo $this->esc($cpuCount ?? 0); ?>">0</p>
            </div>
            <div class="stat-card">
                <h3>GPUs registradas</h3>
                <p class="counter" data-target="<?php echo $this->esc($gpuCount ?? 0); ?>">0</p>
            </div>
        </div>
        <!-- Linea 2 -->
        <div class="stats-container">
            <div class="stat-card">
                <h3>PCs con wifi</h3>
                <p class="counter" data-target="<?php echo $this->esc($wifiCount ?? 0); ?>">0</p>
            </div>
            <div class="stat-card">
                <h3>PCs con bluetooth</h3>
                <p class="counter" data-target="<?php echo $this->esc($bluetoothCount ?? 0); ?>">0</p>
            </div>
        </div>
        <!-- Linea 3 -->
        <div class="stats-container" style="margin-top: 20px;">
            <div class="stat-card">
                <h3>PCs con BIOS</h3>
                <p class="counter" data-target="<?php echo $this->esc($biosCount ?? 0); ?>">0</p>
            </div>
            <div class="stat-card">
                <h3>PCs con UEFI</h3>
                <p class="counter" data-target="<?php echo $this->esc($uefiCount ?? 0); ?>">0</p>
            </div>
        </div>
        <!-- Linea 4 -->
        <div class="stats-container">
            <div class="stat-card">
                <h3>PCs Configuradas</h3>
                <p class="counter" data-target="<?php echo $this->esc($pcCount ?? 0); ?>">0</p>
            </div>
            <div class="stat-card">
                <h3>Modelos</h3>
                <p class="counter" data-target="<?php echo $this->esc($modelsCount ?? 0); ?>">0</p>
            </div>
        </div>
    </div>
    <script src="<?php echo $this->esc(BASE_URL); ?>assets/js/script.js"></script>
</body>

</html>