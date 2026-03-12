<!DOCTYPE html>
<html lang="es-ES">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/jpg" href="<?= BASE_URL ?>assets/favicon.ico" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/script.js"></script>

    <title>Etiquetador OSL</title>
</head>

<body onload="disableSaveAsk()">
    <section class="layout">
        <div class="header">
            <img src="<?= BASE_URL ?>assets/logo_UGR_horizontal.webp" width=350>
            <h1>Generador de etiquetas</h1>
            <img src="<?= BASE_URL ?>assets/osl_logo.webp" width=300>
        </div>
        <div class="options">
            <button class="options-btn" onclick="loadModel()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="32" height="32" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path
                        d="M8 20C8 18.8954 8.89543 18 10 18H26L30 22H54C55.1046 22 56 22.8954 56 24V46C56 47.1046 55.1046 48 54 48H10C8.89543 48 8 47.1046 8 46V20Z" />
                </svg>
                Gestionar Modelos
            </button>
        </div>
        <div class="form">
            <form action="<?= BASE_URL ?>generator/generate_pdf" method="post">
                <div class="form-group">
                    <label for="board_type">Tipo de placa:</label>
                    <select name="board_type" id="board_type" required>
                        <option value="bios">BIOS</option>
                        <option value="uefi">UEFI</option>
                    </select>
                </div>
                <hr>
                <div class="form-group">
                    <label for="cpu_name">Nombre CPU:</label>
                    <div class="line">
                        <select name="cpu_name" id="cpu_name">
                            <option selected disabled>Indefinido</option>
                            <?php foreach ($cpus as $cpu): ?>
                                <option value="<?= htmlspecialchars($cpu['name']) ?>">
                                    <?= htmlspecialchars($cpu['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" placeholder="Escribir nombre del cpu en caso de otro" id="cpu_other_name"
                            name="cpu_other_name" maxlength=25>
                    </div>
                </div>
                <hr>

                <div class="form-group">
                    <label for="ram_capacity">Memoria:</label>
                    <div class="line">
                        <select name="ram_capacity" id="ram_capacity" style="width: 25%;">
                            <option selected disabled>Indefinido</option>
                            <?php foreach ($rams as $ram): ?>
                                <option value="<?= htmlspecialchars($ram['capacity']) ?>">
                                    <?= htmlspecialchars($ram['capacity']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" id="ram_other_capacity" name="ram_other_capacity" placeholder="0"
                            max="99999">
                        GB |
                        <select name="ram_type" id="ram_type" style="width: 19.5%;">
                            <option value="ddr2">DDR2</option>
                            <option value="ddr3">DDR3</option>
                            <option value="ddr4">DDR4</option>
                            <option value="ddr5">DDR5</option>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label for="disc_capacity">Disco duro:</label>
                    <div class="line">
                        <select name="disc_capacity" id="disc_capacity" style="width: 25%;">
                            <option selected disabled>Indefinido</option>
                            <?php foreach ($discs as $disc): ?>
                                <option value="<?= htmlspecialchars($disc['capacity']) ?>">
                                    <?= htmlspecialchars($disc['capacity']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" id="disc_other_capacity" name="disc_other_capacity" placeholder="0"
                            max="99999">
                        GB |
                        <select name="disc_type" id="disc_type" style="width: 19.5%;">
                            <option value="hdd">HDD</option>
                            <option value="ssd">SSD</option>
                            <option value="nvme">NVMe</option>
                        </select>
                    </div>
                </div>
                <hr>

                <div class="form-group">
                    <label for="gpu_name">Gráfica:</label>
                    <div class="line">
                        <select name="gpu_name" id="gpu_name" style="width: 25%;">
                            <option selected disabled>Indefinido</option>
                            <?php foreach ($gpus as $gpu): ?>
                                <option value="<?= htmlspecialchars($gpu['name']) ?>">
                                    <?= htmlspecialchars($gpu['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" placeholder="Escribir nombre del gpu en caso de otro" id="gpu_other_name"
                            name="gpu_other_name" maxlength=18>
                        <select name="gpu_type" id="gpu_type" style="width: 20%">
                            <option value="integrada">Integrada</option>
                            <option value="externa">Externa</option>
                        </select>
                    </div>
                </div>
                <hr>

                <div class="line">
                    <div class="radio-section">
                        <label>WiFi:</label>
                        <div class="radio-input">
                            <label>
                                <input type="radio" id="wifi_si" name="wifi" value="true">
                                <span>Sí</span>
                            </label>
                            <label>
                                <input type="radio" id="wifi_no" name="wifi" value="false">
                                <span>No</span>
                            </label>
                            <span class="selection"></span>
                        </div>

                        <label>Bluetooth:</label>
                        <div class="radio-input">
                            <label>
                                <input type="radio" id="bluetooth_si" name="bluetooth" value="true">
                                <span>Sí</span>
                            </label>
                            <label>
                                <input type="radio" id="bluetooth_no" name="bluetooth" value="false">
                                <span>No</span>
                            </label>
                            <span class="selection"></span>
                        </div>
                    </div>
                    <div class="vertical-line"></div>

                    <div class="twodivinline">
                        <label for="SN">Número de serie:</label>
                        <div class="line">
                            <select name="sn_prefix" id="sn_prefix">
                                <?php foreach ($sns as $sn): ?>
                                    <option value="<?= htmlspecialchars($sn['prefix']) ?>">
                                        <?= htmlspecialchars($sn['prefix']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="text" placeholder="Prefijo (ej: ABC)" id="sn_prefix_other"
                                name="sn_prefix_other" maxlength="3">
                        </div>

                        <label for="num_pag" style="margin-top: 6px">Cantidad de etiquetas</label>
                        <div class="line">
                            <input type="number" placeholder="1" id="num_pag" name="num_pag" max="50" min="1" value="1"
                                required>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="form-group">
                    <label for="observaciones">Observaciones:</label>
                    <textarea id="observaciones" name="observaciones" rows="4" cols="50"
                        placeholder="NO poner más de 5 lineas" maxlength=120></textarea>
                </div>
                <hr>

                <div class="form-group">
                    <label for="cbx">Deseas guardar el modelo:</label>
                    <div class="cntr line">
                        <input type="checkbox" id="cbx" for="cbx" class="hidden-xs-up" name="checkbox_save"
                            value="True">
                        <label for="cbx" class="cbx"></label>
                        <input type="text" placeholder="Nombre del modelo" id="ticket_name" name="ticket_name"
                            maxlength="20">
                    </div>
                </div>
                <input type="submit" value="Generar Preview">
            </form>
        </div>

        <div class="preview">
            <iframe id='iframe_preview' src='' frameborder='0' width='100%' height='100%' title='Preview'
                style='border:none'></iframe>
        </div>

        <footer class="footer">
            <!-- (Abridged slightly for brevity but preserving structure) -->
            <div class="footer-content">
                <div class="footer-buttons">
                    <a href="https://github.com/Adriansolier322/EtiquetadorOSL" class="footer-btn"
                        target="_blank">GitHub</a>
                    <a href="https://osl.ugr.es/" class="footer-btn" target="_blank">Sitio Web</a>
                    <button class="footer-btn" id="theme-toggle">Cambiar Tema</button>
                    <a href="<?= BASE_URL ?>admin" class="footer-btn">Panel administración</a>
                    <a href="<?= BASE_URL ?>logout" class="footer-btn">Cerrar sesión</a>
                </div>
            </div>
            <span class="footer-text">2025 - Oficina de Software Libre</span>
        </footer>
    </section>
</body>

</html>