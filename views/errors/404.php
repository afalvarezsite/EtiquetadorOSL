<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - No Encontrado</title>
    <link rel="icon" type="image/jpg" href="<?= BASE_URL ?>assets/favicon.ico" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: var(--body-bg);
            color: var(--general-color);
            font-family: 'Tomorrow', sans-serif;
            text-align: center;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .error-container {
            padding: 40px;
            background-color: var(--form-bg);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        h1 {
            font-size: 5rem;
            margin: 0;
            color: var(--accent-cyan);
            text-shadow: 0 0 15px var(--accent-glow);
        }

        h2 {
            font-size: 2rem;
            margin: 20px 0;
        }

        p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        a {
            color: var(--accent-cyan);
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #00d9ff;
        }
    </style>
</head>

<body>
    <script>
        // Inicializar tema al inicio del body para evitar parpadeos
        const userPref = localStorage.getItem('theme');
        if (userPref) {
            document.body.classList.add(userPref);
        } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.body.classList.add('dark');
        } else {
            document.body.classList.add('light');
        }
    </script>
    <div class="error-container">
        <h1>404</h1>
        <h2>Página No Encontrada</h2>
        <p>Lo sentimos, la página que buscas no existe o ha sido movida.</p>
        <a href="/">Volver al inicio</a>
    </div>
</body>

</html>