<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrador - HouseGYM</title>
  <link rel="stylesheet" href="assets/admin.css">
</head>

<body>

  <!-- Background glows -->
  <div class="bg-glow bg-glow--top-right"></div>
  <div class="bg-glow bg-glow--bottom-left"></div>

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

      <div class="nav-item nav-item--active" onclick="setSection('dashboard')">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <rect x="3" y="3" width="7" height="7" rx="1" />
          <rect x="14" y="3" width="7" height="7" rx="1" />
          <rect x="3" y="14" width="7" height="7" rx="1" />
          <rect x="14" y="14" width="7" height="7" rx="1" />
        </svg>
        Dashboard
      </div>

      <div class="nav-item" onclick="window.location.href='index.php?route=admin_usuarios'">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
          <circle cx="9" cy="7" r="4" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
        </svg>
        Usuarios
      </div>

      <div class="nav-section-label">Contenido</div>

      <div class="nav-item" onclick="window.location.href='index.php?route=admin_rutina_global'">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        Rutina Global
      </div>

      <div class="nav-item" onclick="window.location.href='index.php?route=admin_rutina_personalizada'">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
        </svg>
        Rutinas Personalizadas
      </div>

      <div class="nav-item" onclick="window.location.href='index.php?route=admin_maquinas'">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <rect x="2" y="7" width="4" height="10" rx="1" />
          <rect x="18" y="7" width="4" height="10" rx="1" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 10h12M6 14h12" />
        </svg>
        Maquinas
      </div>

      <div class="nav-item" onclick="setSection('ejercicios')">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
        Ejercicios
      </div>

      <div class="nav-item" onclick="setSection('dietas')">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
        </svg>
        Dietas
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

  <!-- MAIN -->
  <div class="main-wrap">

    <!-- TOPBAR -->
    <div class="topbar">
      <button class="topbar__menu-btn" id="menuBtn" onclick="toggleSidebar()">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>

      <!-- Search -->
      <div class="search-wrap">
        <svg class="search-wrap__icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"
          stroke-width="2.5">
          <circle cx="11" cy="11" r="8" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35" />
        </svg>
        <input type="text" class="search-input" id="globalSearch" placeholder="Buscar usuario, rutina, maquinas"
          oninput="handleSearch(this.value)">
        <button class="search-clear-btn" onclick="clearSearch()">&#x2022;</button>

        <div class="search-results hidden" id="searchResults"></div>
      </div>

      <!-- User info -->
      <div class="topbar-user">
        <div class="topbar-user__dot"></div>
        <span class="topbar-user__name" id="adminUserLabel">Admin</span>
        <div class="topbar-user__avatar">A</div>
      </div>
    </div>

    <!-- CONTENT -->
    <div class="content">

      <!-- Page title -->
      <div class="page-title">
        <h1>Panel <span>Administrador</span></h1>
        <p class="page-subtitle" id="pageSubtitle">Gestion completa de HouseGYM</p>
      </div>

      <!-- STATS ROW -->
      <div class="stats-grid">

        <div class="stat-card">
          <div class="stat-card__icon stat-card__icon--red">
            <svg width="20" height="20" fill="none" stroke="#e51a2c" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
            </svg>
          </div>
          <div>
            <div class="stat-card__value" id="statUsuarios">0</div>
            <div class="stat-card__label">Usuarios registrados</div>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-card__icon stat-card__icon--green">
            <svg width="20" height="20" fill="none" stroke="#4ade80" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
            </svg>
          </div>
          <div>
            <div class="stat-card__value" id="statRutinas">0</div>
            <div class="stat-card__label">Rutinas personalizadas</div>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-card__icon stat-card__icon--yellow">
            <svg width="20" height="20" fill="none" stroke="#fbbf24" viewBox="0 0 24 24" stroke-width="2">
              <rect x="2" y="7" width="4" height="10" rx="1" />
              <rect x="18" y="7" width="4" height="10" rx="1" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 10h12M6 14h12" />
            </svg>
          </div>
          <div>
            <div class="stat-card__value" id="statMaquinas">0</div>
            <div class="stat-card__label">Maquinas</div>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-card__icon stat-card__icon--purple">
            <svg width="20" height="20" fill="none" stroke="#a78bfa" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
          </div>
          <div>
            <div class="stat-card__value" id="statDietas">0</div>
            <div class="stat-card__label">Dietas activas</div>
          </div>
        </div>

      </div><!-- /stats-grid -->

      <!-- TWO COLUMN -->
      <div class="two-col">

        <!-- Agregar Usuario -->
        <div class="card">
          <div class="card__header">
            <div class="card__accent-bar"></div>
            <span class="card__title">Agregar Usuario</span>
          </div>

          <div class="form-group">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-input" id="userNombre" placeholder="Nombre completo" required>
          </div>

          <div class="form-group">
            <label class="form-label">Documento (Cedula)</label>
            <input type="text" inputmode="numeric" class="form-input" id="userCedula" placeholder="# documento"
              pattern="[0-9]*" oninput="this.value = this.value.replace(/\D/g, '')">
          </div>

          <div class="form-group" style="margin-bottom:16px;">
            <label class="form-label">Contrasena</label>
            <input type="password" class="form-input" id="userPassword" placeholder="********">
          </div>

          <!-- Toggles -->
          <!-- Toggles -->
          <div class="toggle-group">
            <!-- GÉNERO -->
            <div class="form-group" style="margin-bottom: 16px;">
              <label class="form-label"
                style="font-size: 11px; letter-spacing: 0.08em; color: #888; text-transform: uppercase; margin-bottom: 8px; display: block;">Género</label>
              <select id="selectGenero" class="form-input"
                style="padding: 10px 12px; font-size: 14px; background-color: #1e1e1e; color: #fff; border: 1px solid #333; border-radius: 6px; width: 100%; appearance: none; -webkit-appearance: none; background-image: url('data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'12\' height=\'12\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23888\' stroke-width=\'2\'><polyline points=\'6 9 12 15 18 9\'></polyline></svg>'); background-repeat: no-repeat; background-position: right 12px center; cursor: pointer;">
                <option value="" disabled selected>Seleccionar género</option>
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
              </select>
            </div>

            <div class="toggle-row">
              <div>
                <div class="toggle-label-main">Rutina <span>Personalizada</span></div>
                <div class="toggle-label-sub">Acceso a rutina de pago</div>
              </div>
              <label class="toggle-switch">
                <input type="checkbox" id="toggleRutina" onchange="updateToggleState('rutina', this.checked)">
                <span class="toggle-switch__track"></span>
              </label>
            </div>
            <div class="toggle-row">
              <div>
                <div class="toggle-label-main">Acceso a <span>Dieta</span></div>
                <div class="toggle-label-sub">Acceso a dietas de pago</div>
              </div>
              <label class="toggle-switch">
                <input type="checkbox" id="toggleDieta" onchange="toggleDietaSelect(this.checked)">
                <span class="toggle-switch__track"></span>
              </label>
            </div>
            <div id="dietaSelectContainer" style="display: none; margin-top: 10px;">
              <label class="form-label" style="font-size: 12px; margin-bottom: 4px;">Tipo de Dieta</label>
              <select id="selectDieta" class="form-input" style="padding: 8px; font-size: 14px;">
                <option value="1">Hipercalórica</option>
                <option value="2">Normocalórica</option>
                <option value="3">Hipocalórica</option>
              </select>
            </div>
          </div>

          <div class="form-actions">
            <button class="btn btn--ghost" onclick="clearForm()">Limpiar</button>
            <button class="btn btn--primary" onclick="addUser()">
              <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
              </svg>
              Agregar
            </button>
          </div>

          <div class="feedback-msg feedback-msg--success" id="successMsg">
            Usuario agregado correctamente
          </div>
        </div>

        <!-- Ultimos Registrados -->
        <div class="card">
          <div class="card__header card__header--space-between">
            <div style="display:flex;align-items:center;gap:8px;">
              <div class="card__accent-bar"></div>
              <span class="card__title">Ultimos Registrados</span>
            </div>
            <span class="card__link" onclick="window.location.href='index.php?route=admin_usuarios'">Ver todos</span>
          </div>
          <div id="userList"><!-- Populated by JS --></div>
        </div>

      </div><!-- /two-col -->

      <!-- BOTTOM: Chart + Quick Actions -->
      <div class="two-col">

        <!-- Chart -->
        <div class="card">
          <div class="card__header card__header--space-between">
            <div style="display:flex;align-items:center;gap:8px;">
              <div class="card__accent-bar"></div>
              <span class="card__title">Usuarios Registrados</span>
            </div>
            <span style="font-size:11px;color:#3a3a3a;font-weight:500;">ultimo mes</span>
          </div>
          <div class="chart-bars" id="chartBars"><!-- Populated by JS --></div>
          <div class="chart-labels" id="chartLabels"><!-- Populated by JS --></div>
        </div>

        <!-- Acciones Rapidas -->
        <div class="card">
          <div class="card__header">
            <div class="card__accent-bar"></div>
            <span class="card__title">Acciones Rapidas</span>
          </div>
          <div class="actions-grid">
            <div class="action-btn" onclick="setSection('maquinas')">
              <svg width="22" height="22" fill="none" stroke="#e51a2c" viewBox="0 0 24 24" stroke-width="2">
                <rect x="2" y="7" width="4" height="10" rx="1" />
                <rect x="18" y="7" width="4" height="10" rx="1" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 10h12M6 14h12" />
              </svg>
              <div class="action-btn__title">Maquinas</div>
              <div class="action-btn__sub">Gestionar catalogo</div>
            </div>
            <div class="action-btn" onclick="setSection('ejercicios')">
              <svg width="22" height="22" fill="none" stroke="#fbbf24" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
              <div class="action-btn__title">Ejercicios</div>
              <div class="action-btn__sub">Ver y editar</div>
            </div>
            <div class="action-btn" onclick="setSection('rutinas-personalizadas')">
              <svg width="22" height="22" fill="none" stroke="#a78bfa" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
              </svg>
              <div class="action-btn__title">Rutinas</div>
              <div class="action-btn__sub">Activar / Desactivar</div>
            </div>
            <div class="action-btn" onclick="setSection('dietas')">
              <svg width="22" height="22" fill="none" stroke="#4ade80" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
              </svg>
              <div class="action-btn__title">Dietas</div>
              <div class="action-btn__sub">Hipercal / Normo / Hipo</div>
            </div>
          </div>
        </div>

      </div><!-- /two-col bottom -->

    </div><!-- /content -->
  </div><!-- /main-wrap -->

  <script>
    /* ── Mobile sidebar ── */
    function isMobileView() { return window.innerWidth <= 900; }

    function closeSidebar() {
      document.getElementById('sidebar').classList.remove('sidebar--open');
      document.getElementById('sidebarOverlay').classList.remove('sidebar-overlay--visible');
      document.body.style.overflow = '';
    }

    function openSidebar() {
      if (!isMobileView()) return;
      document.getElementById('sidebar').classList.add('sidebar--open');
      document.getElementById('sidebarOverlay').classList.add('sidebar-overlay--visible');
      document.body.style.overflow = 'hidden';
    }

    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.contains('sidebar--open') ? closeSidebar() : openSidebar();
    }

    if (window.innerWidth <= 900) document.getElementById('menuBtn').style.display = 'block';
    window.addEventListener('resize', () => {
      document.getElementById('menuBtn').style.display = window.innerWidth <= 900 ? 'block' : 'none';
      if (window.innerWidth > 900) closeSidebar();
    });

    /* ── Nav section ── */
    function setSection(section) {
      document.querySelectorAll('.nav-item').forEach(i => {
        i.classList.remove('nav-item--active');
      });
      if (event && event.currentTarget && event.currentTarget.classList) {
        event.currentTarget.classList.add('nav-item--active');
      }
      closeSidebar();

      const subtitles = {
        'dashboard': 'Gestion completa de HouseGYM',
        'usuarios': 'Listado y gestion de usuarios desde la tabla usuarios',
        'rutina-global': 'Rutina disponible para todos desde rutina_global',
        'rutinas-personalizadas': 'Rutinas de acceso por pago desde rutina_personalizada',
        'maquinas': 'Catalogo de maquinas por piso desde maquinas',
        'ejercicios': 'Biblioteca de ejercicios desde ejercicios',
        'dietas': 'Planes de dieta desde dietas',
      };
      document.getElementById('pageSubtitle').textContent = subtitles[section] || '';
    }

    /* ── API ── */
    const API_URL = 'index.php';

    async function apiRequest(resource, options = {}) {
      const response = await fetch(`${API_URL}?route=admin_api&resource=${resource}`, {
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', ...(options.headers || {}) },
        ...options,
      });

      if (response.status === 401) {
        window.location.href = 'index.php?route=login&error=no_autorizado';
        return null;
      }

      const data = await response.json();
      if (!response.ok || !data.ok) throw new Error(data.error || 'request_failed');
      return data;
    }

    /* ── Dashboard ── */
    async function loadDashboard() {
      try {
        const data = await apiRequest('dashboard');
        if (!data) return;
        document.getElementById('statUsuarios').textContent = data.stats.usuarios || 0;
        document.getElementById('statRutinas').textContent = data.stats.rutinas_personalizadas || 0;
        document.getElementById('statMaquinas').textContent = data.stats.maquinas || 0;
        document.getElementById('statDietas').textContent = data.stats.dietas || 0;
        renderUsers(data.recent_users);
        renderChart(data.chart);
      } catch (e) {
        showMessage('No se pudo cargar la base de datos del panel.', true);
      }
    }

    /* ── Render users ── */
    function userLabel(u) { return u.nombre || `Usuario ${u.id_usuario || ''}`.trim(); }

    function renderUsers(list) {
      const el = document.getElementById('userList');
      if (!list || !list.length) {
        el.innerHTML = '<div style="padding:18px;color:#5a5a5a;font-size:13px;text-align:center;">No hay usuarios registrados.</div>';
        return;
      }
      el.innerHTML = list.map(u => {
        const label = userLabel(u);
        const initials = (u.nombre || `U${u.id_usuario || ''}`).split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase();
        const activeBadge = Number(u.activo) === 1
          ? '<span class="badge badge--active">Activo</span>'
          : '<span class="badge badge--neutral">Inactivo</span>';
        const planBadge = Number(u.plan_personalizado) === 1
          ? '<span class="badge badge--rutina">Rutina</span>'
          : '<span class="badge badge--neutral">Basico</span>';
        return `
          <div class="user-row">
            <div class="user-row__left">
              <div class="user-avatar">${initials}</div>
              <div>
                <div class="user-name">${label}</div>
                <div class="user-cedula">CC ${u.cedula}</div>
              </div>
            </div>
            <div class="user-row__right">
              ${activeBadge}${planBadge}
              <button class="delete-btn" onclick="deleteUser(this, ${Number(u.id_usuario)})" title="Eliminar usuario">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <polyline points="3 6 5 6 21 6"/>
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
                </svg>
              </button>
            </div>
          </div>`;
      }).join('');
    }

    /* ── Render chart ── */
    function renderChart(chartData) {
      const data = chartData && chartData.length ? chartData : [{ month: 'Hoy', val: 0 }];
      const max = Math.max(1, ...data.map(d => Number(d.val) || 0));

      document.getElementById('chartBars').innerHTML = data.map(d => `
        <div class="chart-bar-wrap" title="${d.val} usuarios">
          <div class="chart-bar-fill" style="height:${((Number(d.val) || 0) / max) * 100}%;"></div>
        </div>`).join('');

      document.getElementById('chartLabels').innerHTML = data.map(d => `
        <div class="chart-label">${d.month}</div>`).join('');
    }

    /* ── Feedback ── */
    function showMessage(text, isError = false) {
      const msg = document.getElementById('successMsg');
      msg.textContent = text;
      msg.style.display = 'block';
      if (isError) {
        msg.classList.add('feedback-msg--error');
        msg.classList.remove('feedback-msg--success');
      } else {
        msg.classList.add('feedback-msg--success');
        msg.classList.remove('feedback-msg--error');
      }
      setTimeout(() => msg.style.display = 'none', 3500);
    }

    /* ── Form ── */
    function clearForm() {
      document.getElementById('userNombre').value = '';
      document.getElementById('userCedula').value = '';
      document.getElementById('userPassword').value = '';
      document.getElementById('toggleRutina').checked = false;
      document.getElementById('toggleDieta').checked = false;
      document.getElementById('dietaSelectContainer').style.display = 'none';
      document.getElementById('successMsg').style.display = 'none';
      document.querySelectorAll('.form-input').forEach(i => i.style.borderColor = '');
    }

    function toggleDietaSelect(checked) {
      document.getElementById('dietaSelectContainer').style.display = checked ? 'block' : 'none';
    }

    async function addUser() {
      const nombre = document.getElementById('userNombre');
      const cedula = document.getElementById('userCedula');
      const password = document.getElementById('userPassword');
      let ok = true;

      [nombre, cedula, password].forEach(input => {
        const empty = !input.value.trim();
        input.style.borderColor = empty ? 'rgba(229,26,44,0.5)' : '';
        if (empty) ok = false;
      });

      if (!ok) return;

      try {
        await apiRequest('users', {
          method: 'POST',
          body: JSON.stringify({
            nombre: nombre.value.trim(),
            cedula: cedula.value.trim(),
            contrasena: password.value,
            plan_personalizado: document.getElementById('toggleRutina').checked ? 1 : 0,
            dieta: document.getElementById('toggleDieta').checked ? document.getElementById('selectDieta').value : 0,
          }),
        });
        clearForm();
        showMessage('Usuario agregado correctamente');
        loadDashboard();
      } catch (error) {
        const msg = error.message === 'base_de_datos'
          ? 'No se pudo guardar. Revisa si la cedula ya existe.'
          : 'No se pudo crear el usuario.';
        showMessage(msg, true);
      }
    }

    async function deleteUser(btn, id) {
      if (!id || !confirm('Eliminar este usuario?')) return;
      try {
        await apiRequest(`users&id=${encodeURIComponent(id)}`, { method: 'DELETE' });
        const row = btn.closest('.user-row');
        row.style.opacity = '0';
        row.style.transform = 'translateX(20px)';
        row.style.transition = 'all 0.25s';
        setTimeout(loadDashboard, 250);
      } catch (e) {
        showMessage('No se pudo eliminar el usuario.', true);
      }
    }

    /* ── Search ── */
    function renderSearchResults(results) {
      const box = document.getElementById('searchResults');
      if (!results.length) {
        box.innerHTML = '<div class="search-result-item"><div style="font-size:13px;color:#5a5a5a;">Sin resultados</div></div>';
        return;
      }
      box.innerHTML = results.map(item => `
        <div class="search-result-item">
          <div class="search-result-avatar">${String(item.tipo || '?').charAt(0).toUpperCase()}</div>
          <div>
            <div class="search-result-title">${item.titulo}</div>
            <div class="search-result-detail">${item.detalle}</div>
          </div>
          <span class="search-result-badge">${item.tipo}</span>
        </div>`).join('');
    }

    let searchTimer = null;
    function handleSearch(val) {
      const box = document.getElementById('searchResults');
      clearTimeout(searchTimer);
      if (val.trim().length < 2) {
        box.classList.add('hidden');
        box.innerHTML = '';
        return;
      }
      box.classList.remove('hidden');
      searchTimer = setTimeout(async () => {
        try {
          const data = await apiRequest(`search&q=${encodeURIComponent(val.trim())}`);
          if (data) renderSearchResults(data.results || []);
        } catch (e) {
          renderSearchResults([]);
        }
      }, 250);
    }

    function clearSearch() {
      document.getElementById('globalSearch').value = '';
      const box = document.getElementById('searchResults');
      box.classList.add('hidden');
      box.innerHTML = '';
    }

    document.addEventListener('click', e => {
      if (!e.target.closest('.search-wrap')) clearSearch();
    });

    function updateToggleState(type, val) { /* ready for backend */ }

    window.renderUsers = renderUsers;
    window.renderChart = renderChart;
    window.handleSearch = handleSearch;
    window.clearSearch = clearSearch;
    window.clearForm = clearForm;
    window.addUser = addUser;
    window.deleteUser = deleteUser;
    window.updateToggleState = updateToggleState;
    window.openSidebar = openSidebar;
    window.closeSidebar = closeSidebar;
    window.toggleSidebar = toggleSidebar;
    window.setSection = setSection;

    /* ── Boot ── */
    apiRequest('session')
      .then(data => {
        if (data && data.admin) {
          const el = document.getElementById('adminUserLabel');
          if (el) el.textContent = data.admin.usuario;
        }
        loadDashboard();
      })
      .catch(() => {
        window.location.href = 'index.php?route=login&error=no_autorizado';
      });
  </script>

</body>

</html>