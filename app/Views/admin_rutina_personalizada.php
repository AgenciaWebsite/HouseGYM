<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rutina Personalizada - HouseGYM</title>
  <link rel="stylesheet" href="assets/admin.css">
  <link rel="stylesheet" href="assets/admin_rutina_personalizada.css">
</head>

<body>

  <!-- Background glows -->
  <div class="bg-glow bg-glow--top-right"></div>
  <div class="bg-glow bg-glow--bottom-left"></div>

  <?php $current_page = 'rutina_personalizada'; include 'admin_sidebar.php'; ?>

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
        <h1>Rutina <span>Personalizada</span></h1>
        <p class="page-subtitle">Editor de ejercicios por usuario — asigna días y ejercicios individuales</p>
      </div>

      <!-- MAIN LAYOUT: izquierda = usuario + días | derecha = catálogo ejercicios -->
      <div class="rp-layout">

        <!-- ── PANEL IZQUIERDO: usuario seleccionado + días de rutina ── -->
        <div class="rp-panel rp-panel--left">

          <!-- Header del panel: selector de usuario -->
          <div class="rp-panel-header">
            <div class="rp-header-user" id="rpHeaderUser">
              <span class="rp-header-user__name" id="rpUserName" onclick="toggleUserPicker()">Seleccionar usuario</span>
              <button class="rp-header-user__btn" id="rpUserBtn" onclick="toggleUserPicker()">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <!-- Dropdown de usuarios -->
              <div class="rp-user-picker" id="rpUserPicker" style="display:none">
                <div class="rp-user-picker__search">
                  <input type="text" id="rpPickerSearch" placeholder="Buscar usuario..."
                    oninput="filterPickerUsers(this.value)" autocomplete="off">
                </div>
                <div class="rp-user-picker__list" id="rpPickerList">
                  <div class="rp-picker-empty">Cargando...</div>
                </div>
              </div>
            </div>

            <div class="rp-header-sep"></div>
            <span class="rp-header-label">rutina</span>
          </div>

          <!-- Lista de días con ejercicios -->
          <div class="rp-days-wrap" id="rpDaysList">
            <div class="rp-empty-state">
              <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
              </svg>
              <p>Selecciona un usuario<br>para editar su rutina</p>
            </div>
          </div>

          <!-- Footer: añadir día y guardar -->
          <div class="rp-panel-footer" id="rpPanelFooter"
            style="display:none; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap;">
            <button class="rp-add-day-btn" onclick="addDay()">
              <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
              </svg>
              Añadir día
            </button>
            <button class="rp-save-btn" onclick="manualSaveRutina()">
              <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              Guardar Cambios
            </button>
          </div>

        </div><!-- /panel izquierdo -->

        <!-- ── PANEL DERECHO: catálogo de ejercicios ── -->
        <div class="rp-panel rp-panel--right">

          <!-- Header del panel derecho -->
          <div class="rp-panel-header rp-panel-header--right">
            <span class="rp-header-label rp-header-label--main">ejercicios</span>
          </div>

          <!-- Barra de búsqueda + filtro -->
          <div class="rp-catalog-search">
            <div class="rp-search-bar">
              <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <circle cx="11" cy="11" r="8" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35" />
              </svg>
              <input type="text" id="catalogSearch" class="rp-search-input" placeholder="Buscar ejercicio..."
                oninput="filterCatalog(this.value)" autocomplete="off">
            </div>
            <div class="rp-filter-wrap">
              <button class="rp-filter-btn" id="rpFilterBtn" onclick="toggleFilterMenu()">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                </svg>
              </button>
              <button class="rp-filter-clear" id="rpFilterClear" onclick="clearFilter()" style="display:none"
                title="Quitar filtro">&times;</button>

              <!-- Dropdown de grupos musculares -->
              <div class="rp-filter-menu" id="rpFilterMenu" style="display:none">
                <div class="rp-filter-menu__title">Grupo muscular</div>
                <div class="rp-filter-menu__list" id="rpFilterList"></div>
              </div>
            </div>
          </div>

          <!-- Grid de ejercicios -->
          <div class="rp-catalog-grid" id="rpCatalogGrid">
            <div class="rp-catalog-loading">Cargando ejercicios...</div>
          </div>

        </div><!-- /panel derecho -->

      </div><!-- /rp-layout -->

    </div><!-- /content -->
  </div><!-- /main-wrap -->

  <!-- MODAL: configurar reps/series al agregar ejercicio a un día -->
  <div class="rp-modal-overlay" id="rpModalOverlay" onclick="closeModal()">
    <div class="rp-modal" onclick="event.stopPropagation()">
      <div class="rp-modal__header">
        <span class="rp-modal__title" id="modalTitle">Agregar ejercicio</span>
        <button class="rp-modal__close" onclick="closeModal()">&times;</button>
      </div>
      <div class="rp-modal__body">
        <div class="rp-modal__exercise-preview" id="modalPreview"></div>
        <div class="rp-modal__fields">
          <div class="rp-modal__field">
            <label class="rp-modal__label">Repeticiones</label>
            <input type="number" class="rp-modal__input" id="modalReps" min="1" value="12" placeholder="12">
          </div>
          <div class="rp-modal__field">
            <label class="rp-modal__label">Series</label>
            <input type="number" class="rp-modal__input" id="modalSeries" min="1" value="3" placeholder="3">
          </div>
        </div>
      </div>
      <div class="rp-modal__footer">
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
       HouseGYM — Admin Rutina Personalizada JS
    ══════════════════════════════════════════ */



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
    let allUsers = [];   // todos los usuarios
    let currentUser = null; // usuario seleccionado
    let rutinaDays = [];   // días de la rutina del usuario actual
    let allEjercicios = [];   // catálogo completo de ejercicios
    let filteredEjerc = [];   // ejercicios filtrados en el catálogo
    let activeFilter = null; // grupo muscular activo
    let pendingExercise = null; // ejercicio pendiente de confirmar en modal
    let pendingDayIdx = null; // día al que se va a agregar

    /* ══ BOOT ══ */
    apiRequest('session')
      .then(data => {
        if (data && data.admin) {
          const el = document.getElementById('adminUserLabel');
          if (el) el.textContent = data.admin.usuario;
        }
        loadUsers();
        loadEjercicios();
      })
      .catch(() => {
        window.location.href = 'index.php?route=login&error=no_autorizado';
      });

    /* ══ USUARIOS ══ */
    async function loadUsers() {
      try {
        const data = await apiRequest('users');
        allUsers = (data.users || []).filter(u => Number(u.plan_personalizado) === 1);
        renderPickerList(allUsers);
      } catch (e) {
        document.getElementById('rpPickerList').innerHTML = '<div class="rp-picker-empty">Error al cargar usuarios.</div>';
      }
    }

    function renderPickerList(list) {
      const el = document.getElementById('rpPickerList');
      if (!list.length) {
        el.innerHTML = '<div class="rp-picker-empty">Sin usuarios con rutina personalizada.</div>';
        return;
      }
      el.innerHTML = list.map(u => {
        const name = u.nombre || `Usuario ${u.id_usuario}`;
        const initials = name.split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase();
        return `<div class="rp-picker-item" onclick="selectUser(${u.id_usuario})">
          <div class="rp-picker-avatar">${initials}</div>
          <div>
            <div class="rp-picker-name">${name}</div>
            <div class="rp-picker-doc">CC ${u.cedula || '--'}</div>
          </div>
        </div>`;
      }).join('');
    }

    function filterPickerUsers(q) {
      const filtered = q.trim().length < 1
        ? allUsers
        : allUsers.filter(u => (u.nombre || '').toLowerCase().includes(q.toLowerCase()) || String(u.cedula || '').includes(q));
      renderPickerList(filtered);
    }

    function toggleUserPicker() {
      const picker = document.getElementById('rpUserPicker');
      const isOpen = picker.style.display !== 'none';
      picker.style.display = isOpen ? 'none' : 'block';
      if (!isOpen) document.getElementById('rpPickerSearch').focus();
    }

    function selectUser(id) {
      currentUser = allUsers.find(u => u.id_usuario == id) || null;
      if (!currentUser) return;

      const name = currentUser.nombre || `Usuario ${currentUser.id_usuario}`;
      document.getElementById('rpUserName').textContent = name;
      document.getElementById('rpUserPicker').style.display = 'none';
      document.getElementById('rpPanelFooter').style.display = 'flex';

      loadRutina(currentUser.id_usuario);
    }

    /* ══ RUTINA DÍAS ══ */
    async function loadRutina(userId) {
      document.getElementById('rpDaysList').innerHTML = '<div class="rp-loading">Cargando rutina...</div>';
      try {
        const data = await apiRequest(`rutina_personalizada&user_id=${encodeURIComponent(userId)}`);
        rutinaDays = data.dias || [];
        renderDays();
      } catch (e) {
        rutinaDays = [];
        renderDays();
      }
    }

    function renderDays() {
      const container = document.getElementById('rpDaysList');
      if (!rutinaDays.length) {
        container.innerHTML = `
          <div class="rp-empty-state">
            <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            <p>Sin días configurados.<br>Pulsa <strong>Añadir dia</strong> para comenzar.</p>
          </div>`;
        return;
      }

      container.innerHTML = rutinaDays.map((dia, di) => `
        <div class="rp-day-card" id="dayCard${di}">
          <div class="rp-day-header">
            <span class="rp-day-label">DÍA ${di + 1}</span>
            <button class="rp-day-remove" onclick="removeDay(${di})" title="Eliminar día">
              <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2M3 6h18"/>
              </svg>
            </button>
          </div>
          <div class="rp-exercise-list">
            ${(dia.ejercicios || []).map((ej, ei) => renderExerciseRow(ej, di, ei)).join('')}
            ${!(dia.ejercicios || []).length ? '<div class="rp-no-exercises">Sin ejercicios — arrastra desde el catalogo o selecciona uno</div>' : ''}
          </div>
        </div>`).join('');
    }

    function renderExerciseRow(ej, di, ei) {
      return `
        <div class="rp-exercise-row">
          <div class="rp-exercise-img">
            ${ej.imagen_url
          ? `<img src="${ej.imagen_url}" alt="${ej.nombre}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">`
          : ''}
            <div class="rp-exercise-img__placeholder" ${ej.imagen_url ? 'style="display:none"' : ''}>
              <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
              <span>imagen</span>
            </div>
          </div>
          <div class="rp-exercise-info">
            <div class="rp-exercise-name">${ej.nombre || 'Ejercicio'}</div>
            <div class="rp-exercise-meta">
              REPS = <span>'${ej.reps || 12}'</span> &nbsp; SERIES = <span>'${ej.series || 3}'</span>
            </div>
            <div class="rp-exercise-muscle">${ej.grupo_muscular || 'grupo muscular'}</div>
          </div>
          <button class="rp-exercise-remove" onclick="removeExercise(${di}, ${ei})" title="Quitar ejercicio">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 9l-6 6M9 9l6 6"/>
            </svg>
          </button>
        </div>`;
    }

    function addDay() {
      if (!currentUser) return;
      rutinaDays.push({ ejercicios: [] });
      renderDays();
      saveRutina();
      // Scroll al final
      setTimeout(() => {
        const wrap = document.getElementById('rpDaysList');
        wrap.scrollTop = wrap.scrollHeight;
      }, 50);
    }

    function removeDay(di) {
      if (!confirm(`¿Eliminar el Día ${di + 1} con todos sus ejercicios?`)) return;
      rutinaDays.splice(di, 1);
      renderDays();
      saveRutina();
    }

    function removeExercise(di, ei) {
      rutinaDays[di].ejercicios.splice(ei, 1);
      renderDays();
      saveRutina();
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
        document.getElementById('rpCatalogGrid').innerHTML = '<div class="rp-catalog-loading">Error al cargar ejercicios.</div>';
      }
    }

    function renderCatalog(list) {
      const grid = document.getElementById('rpCatalogGrid');
      if (!list.length) {
        grid.innerHTML = '<div class="rp-catalog-loading">Sin resultados.</div>';
        return;
      }
      grid.innerHTML = list.map(ej => `
        <div class="rp-catalog-card" onclick="openModal(${ej.id_ejercicio})" title="${ej.nombre}">
          <div class="rp-catalog-card__img">
            ${ej.imagen_url
          ? `<img src="${ej.imagen_url}" alt="${ej.nombre}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">`
          : ''}
            <div class="rp-catalog-card__placeholder" ${ej.imagen_url ? 'style="display:none"' : ''}>
              <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>
          </div>
          <div class="rp-catalog-card__info">
            <div class="rp-catalog-card__name">${ej.nombre || 'Ejercicio'}</div>
            <div class="rp-catalog-card__muscle">${ej.grupo_muscular || ''}</div>
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
      document.getElementById('rpFilterList').innerHTML = groups.map(g => `
        <div class="rp-filter-option" onclick="applyFilter('${g}')">${g}</div>`).join('');
    }

    function toggleFilterMenu() {
      const menu = document.getElementById('rpFilterMenu');
      menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
    }

    function applyFilter(group) {
      activeFilter = group;
      document.getElementById('rpFilterMenu').style.display = 'none';
      document.getElementById('rpFilterClear').style.display = 'flex';
      document.getElementById('rpFilterBtn').classList.add('rp-filter-btn--active');
      filterCatalog(document.getElementById('catalogSearch').value);
    }

    function clearFilter() {
      activeFilter = null;
      document.getElementById('rpFilterClear').style.display = 'none';
      document.getElementById('rpFilterBtn').classList.remove('rp-filter-btn--active');
      filterCatalog(document.getElementById('catalogSearch').value);
    }

    /* ══ MODAL reps/series ══ */
    function openModal(ejercicioId) {
      if (!currentUser) {
        alert('Primero selecciona un usuario.');
        return;
      }
      if (!rutinaDays.length) {
        alert('Primero añade al menos un día a la rutina.');
        return;
      }
      pendingExercise = allEjercicios.find(e => e.id_ejercicio == ejercicioId) || null;
      if (!pendingExercise) return;

      document.getElementById('modalTitle').textContent = pendingExercise.nombre || 'Ejercicio';
      document.getElementById('modalPreview').innerHTML = `
        <div class="rp-modal__img">
          ${pendingExercise.imagen_url
          ? `<img src="${pendingExercise.imagen_url}" alt="${pendingExercise.nombre}">`
          : `<div class="rp-modal__img-placeholder">
                <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
               </div>`}
        </div>
        <div>
          <div class="rp-modal__ej-name">${pendingExercise.nombre || ''}</div>
          <div class="rp-modal__ej-muscle">${pendingExercise.grupo_muscular || ''}</div>
          ${rutinaDays.length > 1 ? `
            <div class="rp-modal__day-select-wrap">
              <label class="rp-modal__label">Agregar al día</label>
              <select class="rp-modal__select" id="modalDaySelect">
                ${rutinaDays.map((_, i) => `<option value="${i}">Día ${i + 1}</option>`).join('')}
              </select>
            </div>` : ''}
        </div>`;

      document.getElementById('modalReps').value = 12;
      document.getElementById('modalSeries').value = 3;
      document.getElementById('rpModalOverlay').classList.add('rp-modal-overlay--visible');
    }

    function closeModal() {
      document.getElementById('rpModalOverlay').classList.remove('rp-modal-overlay--visible');
      pendingExercise = null;
    }

    function confirmAddExercise() {
      if (!pendingExercise || !currentUser) return;
      const reps = parseInt(document.getElementById('modalReps').value) || 12;
      const series = parseInt(document.getElementById('modalSeries').value) || 3;
      const dayIdx = rutinaDays.length > 1
        ? parseInt(document.getElementById('modalDaySelect').value)
        : 0;

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
      saveRutina();
    }

    /* ══ SAVE ══ */
    async function saveRutina() {
      if (!currentUser) return;
      try {
        await apiRequest(`rutina_personalizada&user_id=${encodeURIComponent(currentUser.id_usuario)}`, {
          method: 'POST',
          body: JSON.stringify({ dias: rutinaDays }),
        });
      } catch (e) {
        console.warn('No se pudo guardar la rutina:', e.message);
        throw e;
      }
    }

    async function manualSaveRutina() {
      const btn = document.querySelector('.rp-save-btn');
      const originalText = btn.innerHTML;
      try {
        btn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Guardando...';
        btn.style.opacity = '0.7';
        btn.style.pointerEvents = 'none';

        await saveRutina();

        btn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg> ¡Guardado!';
        btn.style.background = '#10b981'; // green
        btn.style.borderColor = '#10b981';

        setTimeout(() => {
          btn.innerHTML = originalText;
          btn.style.background = '';
          btn.style.borderColor = '';
          btn.style.opacity = '1';
          btn.style.pointerEvents = 'auto';
        }, 2000);
      } catch (e) {
        btn.innerHTML = originalText;
        btn.style.opacity = '1';
        btn.style.pointerEvents = 'auto';
        alert('Hubo un error al guardar los cambios.');
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
      if (!e.target.closest('.rp-header-user')) {
        document.getElementById('rpUserPicker').style.display = 'none';
      }
      if (!e.target.closest('.rp-filter-wrap')) {
        document.getElementById('rpFilterMenu').style.display = 'none';
      }
    });
  </script>

</body>

</html>