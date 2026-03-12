<div class="sidebar">
    <h2>Panel de Admin</h2>
    <ul>
        <li><a href="<?= BASE_URL ?>admin">Dashboard</a></li>
        <li><a href="<?= BASE_URL ?>admin/sn">Números de Serie</a></li>
        <li><a href="<?= BASE_URL ?>admin/cpu">CPUs</a></li>
        <li><a href="<?= BASE_URL ?>admin/gpu">GPUs</a></li>
        <li><a href="<?= BASE_URL ?>admin/pc">PCs</a></li>
        <li><a href="<?= BASE_URL ?>admin/models">Modelos</a></li>
        <li><a href="<?= BASE_URL ?>admin/stats">Estadisticas</a></li>
        <hr>
        <li><a href="<?= BASE_URL ?>admin/users">Gestionar usuarios</a></li>
        <li><a href="<?= BASE_URL ?>generator">Volver al etiquetador</a></li>
        <li><a class="footer-btn" id="theme-toggle">Cambiar Tema</a></li>
        <li><a href="<?= BASE_URL ?>logout">Cerrar Sesión</a></li>
    </ul>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const currentPathClean = window.location.pathname.replace('<?= BASE_URL ?>', '/').split('?')[0];
            const menuLinks = document.querySelectorAll('.sidebar ul li a');

            menuLinks.forEach(link => {
                let linkPathClean = link.getAttribute('href').replace('<?= BASE_URL ?>', '/');

                if (currentPathClean === linkPathClean || currentPathClean.startsWith(linkPathClean + '/')) {
                    if (linkPathClean !== '/admin' || currentPathClean === '/admin') {
                        link.parentElement.classList.add('active');
                    }
                }
            });

            const themeToggle = document.getElementById('theme-toggle');
            const body = document.body;
            const currentTheme = localStorage.getItem('theme');
            if (currentTheme) body.classList.add(currentTheme);

            if (themeToggle) {
                themeToggle.addEventListener('click', () => {
                    if (body.classList.contains('dark-theme')) {
                        body.classList.remove('dark-theme');
                        localStorage.setItem('theme', '');
                    } else {
                        body.classList.add('dark-theme');
                        localStorage.setItem('theme', 'dark-theme');
                    }
                });
            }
        });
    </script>
</div>