<div class="admin-header-mobile">
    <button class="menu-toggle" id="mobile-menu-btn">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>
    <span style="font-weight: bold;">Etiquetador OSL</span>
    <div style="width: 40px;"></div> <!-- Spacer for balance -->
</div>

<div class="sidebar-overlay" id="sidebar-overlay"></div>

<div class="sidebar" id="admin-sidebar">
    <style>
        .sidebar ul li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            transition: all 0.2s ease;
        }
        .sidebar ul li a svg {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
            opacity: 0.8;
        }
        .sidebar ul li.active a svg {
            opacity: 1;
        }
        .sidebar h2 {
            padding: 20px;
            margin-bottom: 10px;
            font-size: 1.4rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar hr {
            margin: 15px 20px;
            border: 0;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
    </style>
    <h2>Panel de Admin</h2>
    <ul>
        <li>
            <a href="<?= BASE_URL ?>admin">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                Dashboard
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>admin/sn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 5v14c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2z"></path><path d="M7 7h10"></path><path d="M7 12h10"></path><path d="M7 17h10"></path></svg>
                Números de Serie
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>admin/cpu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="15" x2="23" y2="15"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="15" x2="4" y2="15"></line></svg>
                CPUs
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>admin/gpu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                GPUs
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>admin/pc">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10v6M6 4h12c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H6c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2zM6 10h12M6 14h12"></path></svg>
                PCs
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>admin/models">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                Modelos
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>admin/stats">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                Estadisticas
            </a>
        </li>
        <hr>
        <li>
            <a href="<?= BASE_URL ?>admin/users">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                Gestionar usuarios
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>generator">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Volver al etiquetador
            </a>
        </li>
        <li>
            <a class="footer-btn" id="theme-toggle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                Cambiar Tema
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>logout">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                Cerrar Sesión
            </a>
        </li>
    </ul>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const menuBtn = document.getElementById('mobile-menu-btn');

        if (menuBtn) {
            menuBtn.addEventListener('click', () => {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });
        }

        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
        }

        // Active Link Highlighting
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
    });
</script>