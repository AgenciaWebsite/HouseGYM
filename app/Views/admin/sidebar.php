<?php
/**
 * Admin Sidebar Component
 * Reusable sidebar for all admin pages.
 * @var string $current_page The ID of the current page to highlight in the nav.
 */
?>
<link rel="stylesheet" href="assets/admin/sidebar.css">

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">

  <!-- Logo -->
  <div class="sidebar-logo">
    <div class="sidebar-logo__inner">
      <div class="sidebar-logo__icon">
        <svg width="18" height="18" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
        </svg>
      </div>
      <div>
        <div class="sidebar-logo__name">HouseGYM</div>
        <div class="sidebar-logo__sub">Admin Panel</div>
      </div>
    </div>
  </div>

  <!-- Nav -->
  <nav class="sidebar-nav">
    <div class="nav-section-label">General</div>

    <div class="nav-item <?php echo ($current_page === 'dashboard') ? 'nav-item--active' : ''; ?>"
      onclick="window.location.href='index.php?route=admin'">
      <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <rect x="3" y="3" width="7" height="7" rx="1" />
        <rect x="14" y="3" width="7" height="7" rx="1" />
        <rect x="3" y="14" width="7" height="7" rx="1" />
        <rect x="14" y="14" width="7" height="7" rx="1" />
      </svg>
      Dashboard
    </div>

    <div class="nav-item <?php echo ($current_page === 'usuarios') ? 'nav-item--active' : ''; ?>"
      onclick="window.location.href='index.php?route=admin_usuarios'">
      <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
        <circle cx="9" cy="7" r="4" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
      </svg>
      Usuarios
    </div>

    <div class="nav-section-label">Contenido</div>

    <div class="nav-item <?php echo ($current_page === 'rutina_global') ? 'nav-item--active' : ''; ?>"
      onclick="window.location.href='index.php?route=admin_rutina_global'">
      <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
      </svg>
      Rutina Global
    </div>

    <div class="nav-item <?php echo ($current_page === 'rutina_personalizada') ? 'nav-item--active' : ''; ?>"
      onclick="window.location.href='index.php?route=admin_rutina_personalizada'">
      <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
      </svg>
      Rutinas Personalizadas
    </div>

    <div class="nav-item <?php echo ($current_page === 'dietas') ? 'nav-item--active' : ''; ?>" onclick="
      window.location.href='index.php?route=admin_dieta'">
      <svg width=" 16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
      </svg>
      Dietas
    </div>

    <div class="nav-item <?php echo ($current_page === 'maquinas') ? 'nav-item--active' : ''; ?>"
      onclick=" window.location.href='index.php?route=admin_maquinas'">
      <svg width=" 16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <rect x="2" y="7" width="4" height="10" rx="1" />
        <rect x="18" y="7" width="4" height="10" rx="1" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 10h12M6 14h12" />
      </svg> Maquinas
    </div>

    <div class="nav-item <?php echo ($current_page === 'ejercicios') ? 'nav-item--active' : ''; ?>"
      onclick="window.location.href='index.php?route=admin_ejercicios'">
      <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
      </svg>
      Ejercicios
    </div>
  </nav>

  <!-- Sidebar Footer -->
  <div class="sidebar-footer">
    <div class="nav-item nav-item--logout" onclick="window.location='index.php?route=logout'">
      <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
      </svg>
      Cerrar Sesion
    </div>
  </div>
</aside>

<!-- Mobile overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<script>
  /* ── Mobile sidebar logic ── */
  function openSidebar() {
    document.getElementById('sidebar').classList.add('sidebar--open');
    document.getElementById('sidebarOverlay').classList.add('sidebar-overlay--visible');
    document.body.style.overflow = 'hidden';
  }

  function closeSidebar() {
    document.getElementById('sidebar').classList.remove('sidebar--open');
    document.getElementById('sidebarOverlay').classList.remove('sidebar-overlay--visible');
    document.body.style.overflow = '';
  }

  function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.contains('sidebar--open') ? closeSidebar() : openSidebar();
  }

  /* Handle resize */
  function handleResize() {
    const menuBtn = document.getElementById('menuBtn');
    if (window.innerWidth > 900) {
      closeSidebar();
      if (menuBtn) menuBtn.style.display = 'none';
    } else {
      if (menuBtn) menuBtn.style.display = 'block';
    }
  }

  window.addEventListener('resize', handleResize);
  handleResize(); // Initial call
</script>