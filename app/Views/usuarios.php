<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi Panel - HouseGYM</title>
  <link rel="stylesheet" href="assets/admin.css">
  <link rel="stylesheet" href="assets/usuarios.css">
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
          <div class="sidebar-logo__sub">Mi Panel</div>
        </div>
      </div>
    </div>

    <!-- Nav -->
    <nav class="sidebar-nav">
      <div class="nav-section-label">Personal</div>

      <a class="nav-item nav-item--active" href="index.php?route=usuarios">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
          <circle cx="9" cy="7" r="4" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
        </svg>
        Mi Perfil
      </a>

      <a class="nav-item" href="#seccion-rutina">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
        </svg>
        Mi Rutina
      </a>

      <a class="nav-item" href="#seccion-rutina">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
        </svg>
        Mi Dieta
      </a>

      <a class="nav-item" href="#seccion-rutina">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
        </svg>
        Maquinas
      </a>

      <a class="nav-item" href="#seccion-rutina">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
        </svg>
        Ejercicios
      </a>
    </nav>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
      <a class="nav-item nav-item--logout" href="index.php?route=logout">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        Cerrar Sesion
      </a>
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

      <div style="flex:1"></div>

      <!-- User info -->
      <div class="topbar-user">
        <div class="topbar-user__dot"></div>
        <span class="topbar-user__name" id="topbarUserName">Cargando...</span>
        <div class="topbar-user__avatar" id="topbarAvatar">U</div>
      </div>
    </div>

    <!-- CONTENT -->
    <div class="content">

      <!-- Page Header -->
      <div class="page-title">
        <h1>Bienvenido, <span id="headerUserName">Usuario</span></h1>
        <p class="page-subtitle">Este es tu panel personal. Aquí puedes ver tu plan actual y tu rutina de entrenamiento.
        </p>
      </div>

      <!-- Perfil y Plan -->
      <div class="user-dashboard-top">

        <!-- Profile Card -->
        <div class="user-card">
          <div class="user-card__header">
            <div class="user-card__avatar" id="profileAvatar">U</div>
            <div class="user-card__info">
              <h2 id="profileName">Cargando...</h2>
              <p id="profileDoc">CC ---</p>
            </div>
          </div>
          <div class="user-card__body">
            <div class="status-row">
              <span class="status-label">Estado de la cuenta</span>
              <span class="badge badge--active" id="badgeActivo">Activo</span>
            </div>
            <div class="status-row">
              <span class="status-label">Rutina Personalizada</span>
              <span class="badge badge--neutral" id="badgeRutina">No activa</span>
            </div>
            <div class="status-row">
              <span class="status-label">Plan de Dieta</span>
              <span class="badge badge--neutral" id="badgeDieta">Sin Dieta</span>
            </div>
          </div>
        </div>

        <!-- Banner Info -->
        <div class="info-banner">
          <div class="info-banner__icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="info-banner__text">
            <h3>Información de tu Plan</h3>
            <p>Si deseas actualizar tu plan, adquirir una rutina personalizada o un plan de nutrición (dieta), por favor
              acércate a la recepción de HouseGYM.</p>
          </div>
        </div>
      </div>

      <hr class="section-divider">

      <!-- Rutina Section -->
      <div id="seccion-rutina" class="routine-section">
        <div class="routine-header">
          <h2>Rutina <span>Global</span></h2>
          <p>ejercicios agrupados por semana de entrenamiento.</p>
        </div>

        <div id="routineStateMsg" class="routine-empty-state">

        </div>

        <div id="routineDaysContainer" class="routine-days" style="display: none;">
          <!-- Se llenará vía JS -->
        </div>
      </div>

    </div><!-- /content -->
  </div><!-- /main-wrap -->

  <script>
    /* ══════════════════════════════════════
       MOBILE SIDEBAR
    ══════════════════════════════════════ */
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

    /* ══════════════════════════════════════
       API HELPERS
    ══════════════════════════════════════ */
    const API_URL = 'index.php?route=usuario_api&resource=';

    async function fetchApi(resource) {
      try {
        const response = await fetch(API_URL + resource);
        if (response.status === 401) {
          window.location.href = 'index.php?route=login&error=no_autorizado';
          return null;
        }
        const data = await response.json();
        if (!data.ok) throw new Error(data.error);
        return data;
      } catch (error) {
        console.error('Error fetching ' + resource, error);
        return null;
      }
    }

    /* ══════════════════════════════════════
       LOGIC
    ══════════════════════════════════════ */

    function setInitials(name) {
      const parts = name.trim().split(' ');
      let ini = parts[0][0];
      if (parts.length > 1) ini += parts[1][0];
      const initials = ini.toUpperCase();
      document.getElementById('topbarAvatar').textContent = initials;
      document.getElementById('profileAvatar').textContent = initials;
    }

    function getDietName(id) {
      const diets = { 1: 'Hipercalórica', 2: 'Normocalórica', 3: 'Hipocalórica' };
      return diets[id] || 'Especial';
    }

    async function loadDashboard() {
      const dataProfile = await fetchApi('profile');
      if (dataProfile && dataProfile.profile) {
        const p = dataProfile.profile;

        const firstName = p.nombre.split(' ')[0];
        document.getElementById('topbarUserName').textContent = firstName;
        document.getElementById('headerUserName').textContent = firstName;

        document.getElementById('profileName').textContent = p.nombre;
        document.getElementById('profileDoc').textContent = 'CC ' + p.cedula;
        setInitials(p.nombre);

        // Update badges
        const bActivo = document.getElementById('badgeActivo');
        if (p.activo) {
          bActivo.className = 'badge badge--active';
          bActivo.textContent = 'Activo';
        } else {
          bActivo.className = 'badge badge--neutral';
          bActivo.textContent = 'Inactivo';
        }

        const bRutina = document.getElementById('badgeRutina');
        if (p.plan_personalizado) {
          bRutina.className = 'badge badge--rutina';
          bRutina.textContent = 'Personalizada';
        }

        const bDieta = document.getElementById('badgeDieta');
        if (p.id_dieta) {
          bDieta.className = 'badge badge--dieta';
          bDieta.textContent = getDietName(p.id_dieta);
        }

        if (p.plan_personalizado) {
          loadRoutine();
        } else {
          document.getElementById('routineStateMsg').style.display = 'flex';
          document.getElementById('routineDaysContainer').style.display = 'none';
        }
      }
    }

    async function loadRoutine() {
      const dataRoutine = await fetchApi('routine');
      if (dataRoutine && dataRoutine.routine && dataRoutine.routine.length > 0) {
        document.getElementById('routineStateMsg').style.display = 'none';
        const container = document.getElementById('routineDaysContainer');
        container.style.display = 'grid';

        let html = '';
        dataRoutine.routine.forEach((dia, index) => {
          const numEjs = dia.ejercicios.length;
          let ejHtml = '';

          if (numEjs === 0) {
            ejHtml = '<p class="day-empty">Día de descanso.</p>';
          } else {
            ejHtml = dia.ejercicios.map(ej => `
              <div class="exercise-item">
                <div class="exercise-img" style="background-image: url('${ej.imagen_url || ''}')"></div>
                <div class="exercise-info">
                  <h4>${ej.nombre}</h4>
                  <span>${ej.grupo_muscular}</span>
                </div>
                <div class="exercise-stats">
                  <div><strong>${ej.series}</strong> Series</div>
                  <div><strong>${ej.reps}</strong> Reps</div>
                </div>
              </div>
            `).join('');
          }

          html += `
            <div class="day-card">
              <div class="day-card__header">
                <h3>Día ${index + 1}</h3>
                <span class="day-badge">${numEjs} Ejercicios</span>
              </div>
              <div class="day-card__body">
                ${ejHtml}
              </div>
            </div>
          `;
        });
        container.innerHTML = html;
      }
    }

    // Init
    loadDashboard();
  </script>
</body>

</html>