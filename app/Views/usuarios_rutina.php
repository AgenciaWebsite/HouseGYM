<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi Panel - HouseGYM</title>
  <link rel="stylesheet" href="assets/admin.css">
  <link rel="stylesheet" href="assets/usuarios_rutina.css">
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

      <a class="nav-item" href="index.php?route=usuarios">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
          <circle cx="9" cy="7" r="4" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
        </svg>
        Mi Perfil
      </a>

      <a class="nav-item nav-item--active" href="index.php?route=usuarios_rutina">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
        </svg>
        Mi Rutina
      </a>

      <a class="nav-item" href="#seccion-dieta">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
        </svg>
        Mi Dieta
      </a>

      <a class="nav-item" href="index.php?route=usuarios_maquinas">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <rect x="2" y="7" width="4" height="10" rx="1" />
          <rect x="18" y="7" width="4" height="10" rx="1" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 10h12M6 14h12" />
        </svg>
        Máquinas
      </a>

      <a class="nav-item" href="#seccion-ejercicios">
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
        <p class="page-subtitle">Este es tu panel personal. Aquí puedes ver tu plan actual y tu rutina de entrenamiento.</p>
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

      <!-- ══════════════════════════════════════
           RUTINA SECTION
      ══════════════════════════════════════ -->
      <div id="seccion-rutina" class="routine-section">

        <div class="routine-header">
          <h2>Mi <span>Rutina</span></h2>
          <p>Selecciona un día para ver los ejercicios asignados.</p>
        </div>

        <!-- Empty / loading state -->
        <div id="routineStateMsg" class="routine-empty-state" style="display: none;">
          <div class="empty-icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h3>Sin rutina asignada</h3>
          <p>Aún no tienes una rutina personalizada. Acércate a recepción para que tu entrenador te asigne una.</p>
        </div>

        <!-- Two-panel layout -->
        <div id="routineLayout" class="routine-layout" style="display: none;">

          <!-- LEFT: Days list -->
          <div class="days-panel">
            <div class="days-panel__header">
              <h3>Días de Entrenamiento</h3>
            </div>
            <div class="days-list" id="daysList">
              <!-- Populated by JS -->
            </div>
          </div>

          <!-- RIGHT: Exercises panel -->
          <div class="exercises-panel" id="exercisesPanel" data-hidden="true">
            <div class="exercises-panel__header">
              <h3 id="exercisesPanelTitle">Ejercicios</h3>
              <span class="exercises-panel__day-tag" id="exercisesDayTag" style="display:none">Día —</span>
            </div>
            <div class="exercises-panel__body" id="exercisesPanelBody">
              <!-- Placeholder until a day is selected -->
              <div class="exercises-placeholder">
                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
                <p>Selecciona un día para ver los ejercicios</p>
              </div>
            </div>
          </div>

        </div><!-- /routineLayout -->

      </div><!-- /seccion-rutina -->

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
       HELPERS
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

    /* ══════════════════════════════════════
       ROUTINE — RENDER DAY ROW
    ══════════════════════════════════════ */
    function buildDayRow(dia, index, isRest) {
      const numEjs = dia.ejercicios ? dia.ejercicios.length : 0;
      const maxPills = 6;
      let pillsHtml = '';

      for (let i = 0; i < maxPills; i++) {
        const filled = i < numEjs ? 'day-pill--filled' : '';
        pillsHtml += `<div class="day-pill ${filled}"></div>`;
      }

      const checkSvg = `
        <svg width="10" height="10" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="3.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>`;

      const restClass = isRest ? 'day-row--rest' : '';

      return `
        <div class="day-row ${restClass}" data-day-index="${index}" onclick="selectDay(${index})">
          <div class="day-row__label">
            <div class="day-check">${checkSvg}</div>
            <span>Día ${index + 1}</span>
          </div>
          <div class="day-row__pills">${pillsHtml}</div>
        </div>`;
    }

    /* ══════════════════════════════════════
       ROUTINE — SELECT DAY (show exercises)
    ══════════════════════════════════════ */
    let _routineData = [];

    function selectDay(index) {
      // Update active row
      document.querySelectorAll('.day-row').forEach((row, i) => {
        row.classList.toggle('day-row--active', i === index);
      });

      const dia = _routineData[index];
      const panel = document.getElementById('exercisesPanel');
      const body  = document.getElementById('exercisesPanelBody');
      const tag   = document.getElementById('exercisesDayTag');

      // Show panel on mobile if hidden
      panel.setAttribute('data-hidden', 'false');
      panel.style.display = 'flex';

      // Update header tag
      tag.style.display = 'inline-block';
      tag.textContent = `Día ${index + 1}`;

      const numEjs = dia.ejercicios ? dia.ejercicios.length : 0;

      if (numEjs === 0) {
        body.innerHTML = `
          <div class="exercises-rest">
            <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="opacity:0.3">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
            <p>Día de descanso</p>
          </div>`;
        return;
      }

      const cardsHtml = dia.ejercicios.map(ej => {
        const hasImg = ej.imagen_url && ej.imagen_url.trim() !== '';
        const photoContent = hasImg
          ? `style="background-image: url('${ej.imagen_url}')"`
          : `class="ex-card__photo--empty"`;

        const noImgIcon = hasImg ? '' : `
          <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M13.5 12h.008v.008H13.5V12zm0 0h.008v.008H13.5V12zm4.5-1.929h.008v.008H18V10.07z"/>
          </svg>`;

        return `
          <div class="ex-card">
            <div class="ex-card__photo" ${photoContent}>
              ${noImgIcon}
              <div class="ex-card__stats">
                <span>${ej.series}</span>×${ej.reps}
              </div>
            </div>
            <div class="ex-card__name">${ej.nombre}</div>
            <div class="ex-card__muscle">${ej.grupo_muscular || ''}</div>
          </div>`;
      }).join('');

      body.innerHTML = `<div class="exercises-grid">${cardsHtml}</div>`;

      // On mobile, scroll to exercises panel
      if (window.innerWidth <= 860) {
        panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    }

    /* ══════════════════════════════════════
       ROUTINE — RENDER ALL DAYS
    ══════════════════════════════════════ */
    function renderRoutine(routine) {
      _routineData = routine;
      const list = document.getElementById('daysList');
      let html = '';

      routine.forEach((dia, index) => {
        const isRest = !dia.ejercicios || dia.ejercicios.length === 0;
        html += buildDayRow(dia, index, isRest);
      });

      list.innerHTML = html;
      document.getElementById('routineLayout').style.display = 'grid';

      // Auto-select first non-rest day on desktop
      if (window.innerWidth > 860) {
        const firstActive = routine.findIndex(d => d.ejercicios && d.ejercicios.length > 0);
        if (firstActive >= 0) selectDay(firstActive);
      }
    }

    /* ══════════════════════════════════════
       LOAD DASHBOARD
    ══════════════════════════════════════ */
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

        // Badges
        const bActivo = document.getElementById('badgeActivo');
        bActivo.className = p.activo ? 'badge badge--active' : 'badge badge--neutral';
        bActivo.textContent = p.activo ? 'Activo' : 'Inactivo';

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

        // Load routine
        if (p.plan_personalizado) {
          await loadRoutine();
        } else {
          document.getElementById('routineStateMsg').style.display = 'flex';
          document.getElementById('routineLayout').style.display = 'none';
        }
      }
    }

    async function loadRoutine() {
      const dataRoutine = await fetchApi('routine');
      if (dataRoutine && dataRoutine.routine && dataRoutine.routine.length > 0) {
        document.getElementById('routineStateMsg').style.display = 'none';
        renderRoutine(dataRoutine.routine);
      } else {
        document.getElementById('routineStateMsg').style.display = 'flex';
        document.getElementById('routineLayout').style.display = 'none';
      }
    }

    // Init
    loadDashboard();
  </script>
</body>

</html>
