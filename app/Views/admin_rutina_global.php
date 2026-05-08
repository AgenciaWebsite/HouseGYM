<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rutina Global - HouseGYM</title>
  <link rel="stylesheet" href="assets/admin.css">
  <link rel="stylesheet" href="assets/admin_rutina_global.css">
</head>

<body>

  <!-- Background glows -->
  <div class="bg-glow bg-glow--top-right"></div>
  <div class="bg-glow bg-glow--bottom-left"></div>

  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebar">

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

    <nav class="sidebar-nav">
      <div class="nav-section-label">General</div>

      <div class="nav-item" onclick="window.location.href='index.php?route=admin'">
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

      <div class="nav-item nav-item--active" onclick="window.location.href='index.php?route=admin_rutina_global'">
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

      <div class="nav-item" onclick="window.location.href='index.php?route=admin_ejercicios'">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
        Ejercicios
      </div>

      <div class="nav-item" onclick="window.location.href='index.php?route=admin'">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
        </svg>
        Dietas
      </div>
    </nav>


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

      <!-- Search global -->
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

      <div class="page-title">
        <h1>Rutina global<span>General</span></h1>
        <p class="page-subtitle">Editor de ejercicios por género y semana — define las rutinas predeterminadas</p>
      </div>

      <!-- MAIN LAYOUT -->
      <div class="rg-layout">

        <!-- ── PANEL IZQUIERDO: Configuración de Rutina Global (5 días fijos) ── -->
        <div class="rg-panel rg-panel--left">

          <!-- Header del panel: selectores de Género y Semana -->
          <div class="rg-panel-header">
            <div class="rg-selectors">
              <div class="rg-select-wrap">
                <label for="selectGenero">Género</label>
                <select id="selectGenero" class="rg-select" onchange="loadRutina()">
                  <option value="Hombre">Hombre</option>
                  <option value="Mujer">Mujer</option>
                </select>
              </div>

              <div class="rg-select-wrap">
                <label for="selectSemana">Semana</label>
                <select id="selectSemana" class="rg-select" onchange="loadRutina()">
                  <option value="1">Semana 1</option>
                  <option value="2">Semana 2</option>
                  <option value="3">Semana 3</option>
                  <option value="4">Semana 4</option>
                </select>
              </div>
            </div>

            <button class="rg-btn-save" onclick="saveRutina()" id="btnSaveRutina" style="display:none">
              <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              Guardar Cambios
            </button>
          </div>

          <!-- Lista de los 5 días -->
          <div class="rg-days-wrap" id="rgDaysList">
            <div class="rg-empty-state">
              <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
              </svg>
              <p>Selecciona un <strong>Género</strong> y <strong>Semana</strong><br>para configurar sus ejercicios</p>
            </div>
          </div>

        </div><!-- /panel izquierdo -->

        <!-- ── PANEL DERECHO: catálogo de ejercicios ── -->
        <div class="rg-panel rg-panel--right">

          <div class="rg-panel-header">
            <span style="font-weight: 700; color: #fff; text-transform: uppercase; font-size: 14px;">Catálogo</span>
          </div>

          <!-- Barra de búsqueda + filtro -->
          <div class="rg-catalog-search">
            <div class="rg-search-bar">
              <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <circle cx="11" cy="11" r="8" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35" />
              </svg>
              <input type="text" id="catalogSearch" class="rg-search-input" placeholder="Buscar ejercicio..."
                oninput="filterCatalog(this.value)" autocomplete="off">
            </div>
            <div class="rg-filter-wrap">
              <button class="rg-filter-btn" id="rgFilterBtn" onclick="toggleFilterMenu()">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                </svg>
              </button>

              <!-- Dropdown de grupos musculares -->
              <div class="rp-filter-menu" id="rgFilterMenu"
                style="display:none; position:absolute; right:0; top:45px; background:#222; border:1px solid #333; border-radius:8px; z-index:100; min-width: 150px; box-shadow: 0 4px 12px rgba(0,0,0,0.5);">
                <div
                  style="padding:10px 12px; font-size:11px; color:#888; border-bottom:1px solid #333; font-weight:bold;">
                  GRUPO MUSCULAR</div>
                <div id="rgFilterList" style="max-height:200px; overflow-y:auto;"></div>
                <button id="rgFilterClear" onclick="clearFilter()"
                  style="display:none; width:100%; background:none; border:none; padding:10px; color:#f87171; font-size:12px; cursor:pointer; font-weight:600;">Quitar
                  filtro</button>
              </div>
            </div>
          </div>

          <!-- Grid de ejercicios -->
          <div class="rg-catalog-grid" id="rgCatalogGrid">
            <div style="color:#aaa; font-size:13px; text-align:center; width:100%;">Cargando ejercicios...</div>
          </div>

        </div><!-- /panel derecho -->

      </div><!-- /rg-layout -->

    </div><!-- /content -->
  </div><!-- /main-wrap -->

  <!-- MODAL: configurar reps/series al agregar ejercicio a un día -->
  <div class="rg-modal-overlay" id="rgModalOverlay" onclick="closeModal()">
    <div class="rg-modal" onclick="event.stopPropagation()">
      <div class="rg-modal__header">
        <span class="rg-modal__title" id="modalTitle">Agregar ejercicio</span>
        <button class="rg-modal__close" onclick="closeModal()">&times;</button>
      </div>
      <div class="rg-modal__body">
        <div class="rg-modal__exercise-preview" id="modalPreview"></div>
        <div class="rg-modal__fields">
          <div class="rg-modal__field">
            <label class="rg-modal__label">Repeticiones</label>
            <input type="number" class="rg-modal__input" id="modalReps" min="1" value="12" placeholder="12">
          </div>
          <div class="rg-modal__field">
            <label class="rg-modal__label">Series</label>
            <input type="number" class="rg-modal__input" id="modalSeries" min="1" value="3" placeholder="3">
          </div>
        </div>
      </div>
      <div class="rg-modal__footer">
        <button class="btn btn--ghost" onclick="closeModal()">Cancelar</button>
        <button class="btn btn--primary" onclick="confirmAddExercise()">
          <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
          </svg>
          Agregar
        </button>
      </div>
    </div>
  </div>

  <script>
    /* ══════════════════════════════════════════
       HouseGYM — Admin Rutina Global JS
    ══════════════════════════════════════════ */

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
      document.getElementById('sidebar').classList.contains('sidebar--open') ? closeSidebar() : openSidebar();
    }
    if (window.innerWidth <= 900) document.getElementById('menuBtn').style.display = 'block';
    window.addEventListener('resize', () => {
      document.getElementById('menuBtn').style.display = window.innerWidth <= 900 ? 'block' : 'none';
      if (window.innerWidth > 900) closeSidebar();
    });

    /* ── API ── */
    const API_URL = 'index.php';
    async function apiRequest(resource, options = {}) {
      const response = await fetch(`${API_URL}?route=admin_api&resource=${resource}`, {
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', ...(options.headers || {}) },
        ...options,
      });
      if (response.status === 401) { window.location.href = 'index.php?route=login&error=no_autorizado'; return null; }
      const data = await response.json();
      if (!response.ok || !data.ok) throw new Error(data.error || 'request_failed');
      return data;
    }

    /* ══ STATE ══ */
    let rutinaDays = [];      // 5 días fijos [ { dia: 1, ejercicios: [] }, ... ]
    let allEjercicios = [];   // catálogo completo de ejercicios
    let filteredEjerc = [];   // ejercicios filtrados
    let activeFilter = null;  // grupo muscular activo
    let pendingExercise = null; // ejercicio pendiente de confirmar en modal
    let loadedParams = { genero: 'Hombre', semana: 1 }; // Track current loaded state

    /* ══ BOOT ══ */
    apiRequest('session')
      .then(data => {
        if (data && data.admin) {
          const el = document.getElementById('adminUserLabel');
          if (el) el.textContent = data.admin.usuario;
        }
        loadEjercicios();
        loadRutina(); // Carga la opción por defecto (Hombre, Semana 1)
      })
      .catch(() => {
        window.location.href = 'index.php?route=login&error=no_autorizado';
      });

    /* ══ RUTINA DÍAS (Fijos 5 días) ══ */
    function initEmptyDays() {
      rutinaDays = [];
      for (let i = 1; i <= 5; i++) {
        rutinaDays.push({ dia: i, ejercicios: [] });
      }
    }

    async function loadRutina() {
      const genero = document.getElementById('selectGenero').value;
      const semana = document.getElementById('selectSemana').value;

      loadedParams = { genero, semana };
      document.getElementById('rgDaysList').innerHTML = '<div style="color:#aaa; text-align:center; padding: 40px;">Cargando rutina...</div>';
      document.getElementById('btnSaveRutina').style.display = 'none';

      initEmptyDays();

      try {
        const data = await apiRequest(`rutina_global&genero=${encodeURIComponent(genero)}&semana=${encodeURIComponent(semana)}`);

        // Mapear los datos que vienen del servidor (pueden faltar días si están vacíos)
        if (data.dias && data.dias.length > 0) {
          data.dias.forEach(dbDia => {
            const dayIndex = dbDia.dia - 1;
            if (dayIndex >= 0 && dayIndex < 5) {
              rutinaDays[dayIndex].ejercicios = dbDia.ejercicios || [];
            }
          });
        }

        renderDays();
        document.getElementById('btnSaveRutina').style.display = 'flex';
      } catch (e) {
        renderDays();
        document.getElementById('btnSaveRutina').style.display = 'flex';
      }
    }

    function renderDays() {
      const container = document.getElementById('rgDaysList');
      container.innerHTML = rutinaDays.map((diaObj, di) => `
        <div class="rg-day-card" id="dayCard${di}">
          <div class="rg-day-header">
            <span class="rg-day-label">DÍA ${diaObj.dia}</span>
          </div>
          <div class="rg-exercise-list">
            ${(diaObj.ejercicios || []).map((ej, ei) => renderExerciseRow(ej, di, ei)).join('')}
            ${!(diaObj.ejercicios || []).length ? '<div class="rg-no-exercises">Sin ejercicios — selecciona uno del catálogo</div>' : ''}
          </div>
        </div>`).join('');
    }

    function renderExerciseRow(ej, di, ei) {
      return `
        <div class="rg-exercise-row">
          <div class="rg-exercise-img">
            ${ej.imagen_url
          ? `<img src="${ej.imagen_url}" alt="${ej.nombre}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">`
          : ''}
            <div class="rg-exercise-img__placeholder" ${ej.imagen_url ? 'style="display:none"' : ''}>
              <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
              <span>imagen</span>
            </div>
          </div>
          <div class="rg-exercise-info">
            <div class="rg-exercise-name">${ej.nombre || 'Ejercicio'}</div>
            <div class="rg-exercise-meta">
              REPS = <span>'${ej.reps || 12}'</span> &nbsp; SERIES = <span>'${ej.series || 3}'</span>
            </div>
            <div class="rg-exercise-muscle">${ej.grupo_muscular || 'grupo muscular'}</div>
          </div>
          <button class="rg-exercise-remove" onclick="removeExercise(${di}, ${ei})" title="Quitar ejercicio">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 9l-6 6M9 9l6 6"/>
            </svg>
          </button>
        </div>`;
    }

    function removeExercise(di, ei) {
      rutinaDays[di].ejercicios.splice(ei, 1);
      renderDays();
    }

    /* ══ CATÁLOGO EJERCICIOS ══ */
    async function loadEjercicios() {
      try {
        const data = await apiRequest('ejercicios');
        allEjercicios = data.ejercicios || [];
        filteredEjerc = [...allEjercicios];
        renderCatalog(filteredEjerc);
        buildFilterMenu();
      } catch (e) {
        document.getElementById('rgCatalogGrid').innerHTML = '<div style="color:#f87171; font-size:13px;">Error al cargar ejercicios.</div>';
      }
    }

    function renderCatalog(list) {
      const grid = document.getElementById('rgCatalogGrid');
      if (!list.length) {
        grid.innerHTML = '<div style="color:#aaa; font-size:13px; text-align:center; width:100%;">Sin resultados.</div>';
        return;
      }
      grid.innerHTML = list.map(ej => `
        <div class="rg-catalog-card" onclick="openModal(${ej.id_ejercicio})" title="${ej.nombre}">
          <div class="rg-catalog-card__img">
            ${ej.imagen_url
          ? `<img src="${ej.imagen_url}" alt="${ej.nombre}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">`
          : ''}
            <div class="rg-catalog-card__placeholder" ${ej.imagen_url ? 'style="display:none"' : ''}>
              <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>
          </div>
          <div class="rg-catalog-card__info">
            <div class="rg-catalog-card__name">${ej.nombre || 'Ejercicio'}</div>
            <div class="rg-catalog-card__muscle">${ej.grupo_muscular || ''}</div>
          </div>
        </div>`).join('');
    }

    function filterCatalog(q) {
      const base = activeFilter
        ? allEjercicios.filter(e => (e.grupo_muscular || '').toLowerCase() === activeFilter.toLowerCase())
        : allEjercicios;
      filteredEjerc = q.trim().length < 1
        ? base
        : base.filter(e => (e.nombre || '').toLowerCase().includes(q.toLowerCase()));
      renderCatalog(filteredEjerc);
    }

    function buildFilterMenu() {
      const groups = [...new Set(allEjercicios.map(e => e.grupo_muscular).filter(Boolean))].sort();
      document.getElementById('rgFilterList').innerHTML = groups.map(g => `
        <div style="padding:10px 12px; font-size:12px; color:#ddd; cursor:pointer; border-bottom:1px solid #333;" onmouseover="this.style.background='#333'" onmouseout="this.style.background='transparent'" onclick="applyFilter('${g}')">${g}</div>`).join('');
    }

    function toggleFilterMenu() {
      const menu = document.getElementById('rgFilterMenu');
      menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
    }

    function applyFilter(group) {
      activeFilter = group;
      document.getElementById('rgFilterMenu').style.display = 'none';
      document.getElementById('rgFilterClear').style.display = 'block';
      document.getElementById('rgFilterBtn').classList.add('rg-filter-btn--active');
      filterCatalog(document.getElementById('catalogSearch').value);
    }

    function clearFilter() {
      activeFilter = null;
      document.getElementById('rgFilterClear').style.display = 'none';
      document.getElementById('rgFilterBtn').classList.remove('rg-filter-btn--active');
      filterCatalog(document.getElementById('catalogSearch').value);
    }

    /* ══ MODAL reps/series ══ */
    function openModal(ejercicioId) {
      if (!rutinaDays || rutinaDays.length === 0) {
        alert('Cargando la rutina... por favor espera.');
        return;
      }

      pendingExercise = allEjercicios.find(e => e.id_ejercicio == ejercicioId) || null;
      if (!pendingExercise) return;

      document.getElementById('modalTitle').textContent = pendingExercise.nombre || 'Ejercicio';
      document.getElementById('modalPreview').innerHTML = `
        <div class="rg-modal__img">
          ${pendingExercise.imagen_url
          ? `<img src="${pendingExercise.imagen_url}" alt="${pendingExercise.nombre}">`
          : `<div style="color:#666;"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div>`}
        </div>
        <div>
          <div class="rg-modal__ej-name">${pendingExercise.nombre || ''}</div>
          <div class="rg-modal__ej-muscle">${pendingExercise.grupo_muscular || ''}</div>
          <div style="margin-top: 12px;">
            <label class="rg-modal__label">Agregar al día</label>
            <select class="rg-modal__select" id="modalDaySelect" style="margin-top: 4px;">
              ${rutinaDays.map(d => `<option value="${d.dia - 1}">Día ${d.dia}</option>`).join('')}
            </select>
          </div>
        </div>`;

      document.getElementById('modalReps').value = 12;
      document.getElementById('modalSeries').value = 3;
      document.getElementById('rgModalOverlay').classList.add('rg-modal-overlay--visible');
    }

    function closeModal() {
      document.getElementById('rgModalOverlay').classList.remove('rg-modal-overlay--visible');
      pendingExercise = null;
    }

    function confirmAddExercise() {
      if (!pendingExercise) return;

      const reps = parseInt(document.getElementById('modalReps').value) || 12;
      const series = parseInt(document.getElementById('modalSeries').value) || 3;
      const dayIdx = parseInt(document.getElementById('modalDaySelect').value) || 0;

      if (!rutinaDays[dayIdx].ejercicios) rutinaDays[dayIdx].ejercicios = [];

      rutinaDays[dayIdx].ejercicios.push({
        id_ejercicio: pendingExercise.id_ejercicio,
        nombre: pendingExercise.nombre,
        imagen_url: pendingExercise.imagen_url,
        grupo_muscular: pendingExercise.grupo_muscular,
        reps,
        series,
      });

      closeModal();
      renderDays();
    }

    /* ══ SAVE ══ */
    async function saveRutina() {
      const btn = document.getElementById('btnSaveRutina');
      const originalText = btn.innerHTML;
      btn.innerHTML = 'Guardando...';
      btn.disabled = true;

      try {
        await apiRequest('rutina_global', {
          method: 'POST',
          body: JSON.stringify({
            genero: loadedParams.genero,
            semana: parseInt(loadedParams.semana),
            dias: rutinaDays
          }),
        });

        btn.style.background = '#4ade80';
        btn.style.color = '#000';
        btn.innerHTML = '¡Guardado!';

        setTimeout(() => {
          btn.style.background = '';
          btn.style.color = '';
          btn.innerHTML = originalText;
          btn.disabled = false;
        }, 2000);
      } catch (e) {
        alert('Error al guardar la rutina: ' + e.message);
        btn.innerHTML = originalText;
        btn.disabled = false;
      }
    }

    /* ══ GLOBAL SEARCH (topbar) ══ */
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
      if (val.trim().length < 2) { box.classList.add('hidden'); box.innerHTML = ''; return; }
      box.classList.remove('hidden');
      searchTimer = setTimeout(async () => {
        try {
          const data = await apiRequest(`search&q=${encodeURIComponent(val.trim())}`);
          if (data) renderSearchResults(data.results || []);
        } catch (e) { renderSearchResults([]); }
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
      if (!e.target.closest('.rg-filter-wrap')) {
        document.getElementById('rgFilterMenu').style.display = 'none';
      }
    });
  </script>

</body>

</html>