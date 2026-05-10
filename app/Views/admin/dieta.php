<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dieta Personalizada - HouseGYM</title>
  <link rel="stylesheet" href="assets/admin/dashboard.css">
  <link rel="stylesheet" href="assets/admin/dieta.css">
</head>

<body>

  <div class="bg-glow bg-glow--top-right"></div>
  <div class="bg-glow bg-glow--bottom-left"></div>

  <?php $current_page = 'dietas';
  include 'sidebar.php'; ?>

  <!-- MAIN -->
  <div class="main-wrap">

    <!-- TOPBAR -->
    <div class="topbar">
      <button class="topbar__menu-btn" id="menuBtn" onclick="toggleSidebar()">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>

      <?php include 'search.php'; ?>

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
        <h1>Dieta <span>Personalizada</span></h1>
        <p class="page-subtitle">Asigna un plan de alimentación a cada usuario</p>
      </div>

      <!-- LAYOUT: izquierda = usuario + dieta asignada | derecha = catálogo de dietas -->
      <div class="dp-layout">

        <!-- ── PANEL IZQUIERDO ── -->
        <div class="dp-panel dp-panel--left">

          <!-- Header: selector de usuario -->
          <div class="dp-panel-header">
            <div class="dp-header-user" id="dpHeaderUser">
              <span class="dp-header-user__name" id="dpUserName" onclick="toggleUserPicker()">Seleccionar usuario</span>
              <button class="dp-header-user__btn" id="dpUserBtn" onclick="toggleUserPicker()">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <!-- Dropdown usuarios -->
              <div class="dp-user-picker" id="dpUserPicker" style="display:none">
                <div class="dp-user-picker__search">
                  <input type="text" id="dpPickerSearch" placeholder="Buscar usuario..."
                    oninput="filterPickerUsers(this.value)" autocomplete="off">
                </div>
                <div class="dp-user-picker__list" id="dpPickerList">
                  <div class="dp-picker-empty">Cargando...</div>
                </div>
              </div>
            </div>

            <div class="dp-header-sep"></div>
            <span class="dp-header-label">dieta asignada</span>
          </div>

          <!-- Dieta actualmente asignada -->
          <div class="dp-assigned-wrap" id="dpAssignedWrap" ondragover="handleDragOver(event)"
            ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)">
            <div class="dp-empty-state" id="dpEmptyState">
              <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
              <p>Selecciona un usuario<br>para ver o asignar su dieta</p>
            </div>
          </div>

          <!-- Footer: guardar -->
          <div class="dp-panel-footer" id="dpPanelFooter" style="display:none;">
            <button class="dp-save-btn" onclick="manualSaveDiet()">
              <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              Guardar Cambios
            </button>
          </div>

        </div><!-- /panel izquierdo -->

        <!-- ── PANEL DERECHO: catálogo de dietas ── -->
        <div class="dp-panel dp-panel--right">

          <!-- Header -->
          <div class="dp-panel-header dp-panel-header--right">
            <span class="dp-header-label dp-header-label--main">catálogo de dietas</span>
          </div>

          <!-- Búsqueda y Agregar -->
          <div class="dp-catalog-actions">
            <div class="dp-search-bar" style="margin-bottom:0;">
              <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <circle cx="11" cy="11" r="8" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35" />
              </svg>
              <input type="text" id="dietSearch" class="dp-search-input" placeholder="Buscar dieta..."
                oninput="filterDiets(this.value)" autocomplete="off">
            </div>
            <button class="btn btn--primary dp-btn-add" onclick="openManageDietModal()">
              <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
              </svg>
              agregar
            </button>
          </div>

          <!-- Grid de dietas -->
          <div class="dp-catalog-grid" id="dpCatalogGrid">
            <div class="dp-catalog-loading">Cargando dietas...</div>
          </div>

        </div><!-- /panel derecho -->

      </div><!-- /dp-layout -->

    </div><!-- /content -->
  </div><!-- /main-wrap -->

  <!-- MODAL: confirmar asignación -->
  <div class="dp-modal-overlay" id="dpModalOverlay" onclick="closeModal()">
    <div class="dp-modal" onclick="event.stopPropagation()">
      <div class="dp-modal__header">
        <span class="dp-modal__title" id="modalTitle">Asignar dieta</span>
        <button class="dp-modal__close" onclick="closeModal()">&times;</button>
      </div>
      <div class="dp-modal__body">
        <div class="dp-modal__preview" id="modalPreview"></div>
        <p class="dp-modal__info" id="modalInfo"></p>
      </div>
      <div class="dp-modal__footer">
        <button class="btn btn--ghost" onclick="closeModal()">Cancelar</button>
        <button class="btn btn--primary" onclick="confirmAssign()">
          <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
          </svg>
          Asignar
        </button>
      </div>
    </div>
  </div>

  <!-- MODAL: gestionar dieta -->
  <div class="gm-modal-overlay" id="manageDietModalOverlay" onclick="closeManageDietModal()">
    <div class="gm-modal" onclick="event.stopPropagation()">
      <div class="gm-modal__head">
        <div>
          <span class="gm-modal__title" id="manageModalTitle">Agregar Dieta</span>
        </div>
        <button class="gm-modal__close" onclick="closeManageDietModal()">&times;</button>
      </div>
      <div class="gm-modal__body" style="grid-template-columns: 1fr; gap: 14px;">

        <!-- Foto zone parecida a máquinas -->
        <div class="gm-photo-zone" id="dietPhotoZone" onclick="document.getElementById('dietPhotoInput').click()">
          <input type="file" id="dietPhotoInput" accept="image/*" onchange="previewDietPhoto(event)">
          <div id="dietPhotoPlaceholder" style="display:flex;flex-direction:column;align-items:center;gap:8px;">
            <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
              <rect x="3" y="3" width="18" height="18" rx="2" />
              <circle cx="8.5" cy="8.5" r="1.5" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21" />
            </svg>
            <span class="gm-photo-zone__label">Subir foto<br>de la dieta</span>
          </div>
          <img id="dietPhotoPreview" src="" alt="preview" style="display:none;">
        </div>

        <div class="gm-form-side">
          <div class="gm-form-group">
            <label class="gm-form-label">Nombre de la Dieta</label>
            <input type="text" id="dietTypeInput" class="gm-form-input" placeholder="Ej: Dieta...
          </div>
          <div class=" gm-form-group">
            <label class="gm-form-label">Descripción</label>
            <textarea id="dietDescInput" class="gm-form-textarea" placeholder="Descripción de la dieta..."></textarea>
          </div>
        </div>

      </div>
      <div class="gm-modal__foot">
        <button class="gm-btn gm-btn--danger" id="deleteDietBtn" onclick="deleteDiet()"
          style="display:none; margin-right:auto; text-transform:uppercase; letter-spacing:1px; padding: 12px 24px;">
          <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg> ELIMINAR
        </button>
        <button class="gm-btn gm-btn--ghost" onclick="closeManageDietModal()">Cancelar</button>
        <button class="gm-btn gm-btn--primary" onclick="saveManageDiet()">
          <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
          </svg>
          Guardar
        </button>
      </div>
    </div>
  </div>

  <script>
    /* ══════════════════════════════════════════
       HouseGYM — Admin Dieta Personalizada JS
    ══════════════════════════════════════════ */

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

    /* ══ STATE ══ */
    let allUsers = [];
    let currentUser = null;
    let assignedDiet = null;   // { id_dieta, ... } | null
    let allDiets = [];
    let filteredDiets = [];
    let pendingDiet = null;    // dieta pendiente de confirmar

    /* ══ BOOT ══ */
    apiRequest('session')
      .then(data => {
        if (data && data.admin) {
          const el = document.getElementById('adminUserLabel');
          if (el) el.textContent = data.admin.usuario;
        }
        loadUsers();
        loadDiets();
      })
      .catch(() => {
        window.location.href = 'index.php?route=login&error=no_autorizado';
      });

    /* ══ USUARIOS ══ */
    async function loadUsers() {
      try {
        const data = await apiRequest('users');
        allUsers = data.users || [];
        renderPickerList(allUsers);
      } catch (e) {
        document.getElementById('dpPickerList').innerHTML =
          '<div class="dp-picker-empty">Error al cargar usuarios.</div>';
      }
    }

    function renderPickerList(list) {
      const el = document.getElementById('dpPickerList');
      if (!list.length) {
        el.innerHTML = '<div class="dp-picker-empty">Sin usuarios disponibles.</div>';
        return;
      }
      el.innerHTML = list.map(u => {
        const name = u.nombre || `Usuario ${u.id_usuario}`;
        const initials = name.split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase();
        return `<div class="dp-picker-item" onclick="selectUser(${u.id_usuario})">
          <div class="dp-picker-avatar">${initials}</div>
          <div>
            <div class="dp-picker-name">${name}</div>
            <div class="dp-picker-doc">CC ${u.cedula || '--'}</div>
          </div>
        </div>`;
      }).join('');
    }

    function filterPickerUsers(q) {
      const filtered = q.trim().length < 1
        ? allUsers
        : allUsers.filter(u =>
          (u.nombre || '').toLowerCase().includes(q.toLowerCase()) ||
          String(u.cedula || '').includes(q)
        );
      renderPickerList(filtered);
    }

    function toggleUserPicker() {
      const picker = document.getElementById('dpUserPicker');
      const isOpen = picker.style.display !== 'none';
      picker.style.display = isOpen ? 'none' : 'block';
      if (!isOpen) document.getElementById('dpPickerSearch').focus();
    }

    async function selectUser(id) {
      currentUser = allUsers.find(u => u.id_usuario == id) || null;
      if (!currentUser) return;

      const name = currentUser.nombre || `Usuario ${currentUser.id_usuario}`;
      document.getElementById('dpUserName').textContent = name;
      document.getElementById('dpUserPicker').style.display = 'none';
      document.getElementById('dpPanelFooter').style.display = 'flex';

      await loadAssignedDiet(currentUser.id_usuario);
      renderCatalog(filteredDiets); // re-render para marcar asignada
    }

    /* ══ DIETA ASIGNADA AL USUARIO ══ */
    async function loadAssignedDiet(userId) {
      const wrap = document.getElementById('dpAssignedWrap');
      wrap.innerHTML = '<div class="dp-loading">Cargando dieta...</div>';
      try {
        const data = await apiRequest(`dieta_usuario&user_id=${encodeURIComponent(userId)}`);
        assignedDiet = (data && data.diet && data.diet.id_dieta) ? data.diet : null;
      } catch (e) {
        assignedDiet = null;
      }
      renderAssigned();
    }

    function renderAssigned() {
      const wrap = document.getElementById('dpAssignedWrap');
      if (!assignedDiet) {
        wrap.innerHTML = `
          <div class="dp-empty-state">
            <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <p>Sin dieta asignada.<br>Selecciona una del catálogo.</p>
          </div>`;
        return;
      }

      const diet = allDiets.find(d => d.id_dieta == assignedDiet.id_dieta) || assignedDiet;
      const name = getDietName(diet.id_dieta, diet.tipo || diet.nombre);
      const desc = getDietDesc(diet.id_dieta, diet.descripcion);
      const typeClass = getDietTypeClass(diet.id_dieta);
      const typeLabel = getDietTypeLabel(diet.id_dieta);

      wrap.innerHTML = `
        <div class="dp-assigned-card">
          <div class="dp-assigned-card__header">
            <span class="dp-assigned-card__label">Plan activo</span>
            <span class="dp-assigned-card__badge">Asignada</span>
            <button class="dp-assigned-card__remove" onclick="removeDiet()" title="Quitar dieta">
              <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2M3 6h18"/>
              </svg>
            </button>
          </div>
          <div class="dp-assigned-card__body">
            <div class="dp-assigned-card__icon">
              <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
              </svg>
            </div>
            <div class="dp-assigned-card__info">
              <div class="dp-assigned-card__name">${name}</div>
              <div class="dp-assigned-card__desc">${desc}</div>
              <span class="dp-diet-card__type ${typeClass}" style="margin-top:6px;display:inline-block;">${typeLabel}</span>
            </div>
          </div>
        </div>`;
    }

    function removeDiet() {
      if (!currentUser) return;
      if (!confirm('¿Quitar la dieta asignada a este usuario?')) return;
      assignedDiet = null;
      renderAssigned();
      renderCatalog(filteredDiets);
    }

    /* ══ CATÁLOGO DE DIETAS ══ */
    async function loadDiets() {
      try {
        const data = await apiRequest('dietas');
        allDiets = data.dietas || [];
        filteredDiets = [...allDiets];
        renderCatalog(filteredDiets);
      } catch (e) {
        // Fallback con dietas por defecto si la API no responde aún
        allDiets = [
          { id_dieta: 1, tipo: 'Hipercalórica', descripcion: 'Alta en calorías para ganar masa muscular.' },
          { id_dieta: 2, tipo: 'Normocalórica', descripcion: 'Balance calórico para mantenimiento.' },
          { id_dieta: 3, tipo: 'Hipocalórica', descripcion: 'Déficit calórico para perder grasa.' },
          { id_dieta: 4, tipo: 'Personalizada', descripcion: 'Plan diseñado a medida por el entrenador.' },
        ];
        filteredDiets = [...allDiets];
        renderCatalog(filteredDiets);
      }
    }

    function renderCatalog(list) {
      const grid = document.getElementById('dpCatalogGrid');
      if (!list.length) {
        grid.innerHTML = '<div class="dp-catalog-loading">Sin resultados.</div>';
        return;
      }

      const assignedId = assignedDiet ? assignedDiet.id_dieta : null;

      grid.innerHTML = list.map(diet => {
        const isAssigned = diet.id_dieta == assignedId;
        const name = getDietName(diet.id_dieta, diet.tipo || diet.nombre);
        const desc = getDietDesc(diet.id_dieta, diet.descripcion);
        const typeClass = getDietTypeClass(diet.id_dieta);
        const typeLabel = getDietTypeLabel(diet.id_dieta);

        const heroContent = diet.foto_url
          ? `<img src="${diet.foto_url}" class="dp-hero-image">`
          : `<div class="dp-diet-card__hero-icon">${getDietIcon(diet.id_dieta)}</div>`;

        return `
          <div class="dp-diet-card ${isAssigned ? 'dp-diet-card--assigned' : ''}" 
               style="position:relative;" title="${name}"
               draggable="true" 
               ondragstart="handleDragStart(event, ${diet.id_dieta})"
               ondragend="handleDragEnd(event)">
            <span class="dp-diet-card__chip">Asignada</span>
            <button class="dp-diet-edit-btn" onclick="openManageDietModal(${diet.id_dieta}); event.stopPropagation();" title="Editar Dieta">
              <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                 <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
              </svg>
            </button>
            <div class="dp-diet-card__accent" onclick="openModal(${diet.id_dieta})"></div>
            <div class="dp-diet-card__hero" onclick="openModal(${diet.id_dieta})">
              ${heroContent}
            </div>
            <div class="dp-diet-card__info" onclick="openModal(${diet.id_dieta})">
              <div class="dp-diet-card__name">${name}</div>
              <div class="dp-diet-card__desc">${desc}</div>
              <span class="dp-diet-card__type ${typeClass}">${typeLabel}</span>
            </div>
          </div>`;
      }).join('');
    }

    function filterDiets(q) {
      filteredDiets = q.trim().length < 1
        ? allDiets
        : allDiets.filter(d =>
          (d.tipo || d.nombre || '').toLowerCase().includes(q.toLowerCase()) ||
          getDietDesc(d.id_dieta, d.descripcion).toLowerCase().includes(q.toLowerCase())
        );
      renderCatalog(filteredDiets);
    }

    /* ══ HELPERS ══ */
    function getDietName(id, fallback) {
      return fallback || 'Personalizada';
    }

    function getDietDesc(id, fallback) {
      return fallback || 'Plan personalizado por el entrenador.';
    }

    function getDietTypeClass(id) {
      const map = { 1: 'dp-diet-card__type--hiper', 2: 'dp-diet-card__type--normo', 3: 'dp-diet-card__type--hipo' };
      return map[id] || 'dp-diet-card__type--custom';
    }

    function getDietTypeLabel(id) {
      const map = { 1: 'Volumen', 2: 'Mantenimiento', 3: 'Definición' };
      return map[id] || 'Custom';
    }

    function getDietIcon(id) {
      const icons = {
        1: `<svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.3">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>`,
        2: `<svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.3">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
            </svg>`,
        3: `<svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.3">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>`,
      };
      return icons[id] || `<svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.3">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
      </svg>`;
    }

    /* ══ DRAG AND DROP ══ */
    function handleDragStart(e, dietId) {
      e.dataTransfer.setData('text/plain', dietId);
      e.currentTarget.style.opacity = '0.5';
    }
    function handleDragEnd(e) {
      e.currentTarget.style.opacity = '1';
    }
    function handleDragOver(e) {
      e.preventDefault();
      document.getElementById('dpAssignedWrap').classList.add('dp-dragover');
    }
    function handleDragLeave(e) {
      document.getElementById('dpAssignedWrap').classList.remove('dp-dragover');
    }
    function handleDrop(e) {
      e.preventDefault();
      document.getElementById('dpAssignedWrap').classList.remove('dp-dragover');
      const dietId = e.dataTransfer.getData('text/plain');
      if (dietId) openModal(parseInt(dietId));
    }

    /* ══ MODAL CONFIRMAR ASIGNACIÓN ══ */
    function openModal(dietId) {
      if (!currentUser) {
        alert('Primero selecciona un usuario.');
        return;
      }
      pendingDiet = allDiets.find(d => d.id_dieta == dietId) || { id_dieta: dietId };

      const name = getDietName(pendingDiet.id_dieta, pendingDiet.tipo || pendingDiet.nombre);
      const desc = getDietDesc(pendingDiet.id_dieta, pendingDiet.descripcion);
      const userName = currentUser.nombre || `Usuario ${currentUser.id_usuario}`;

      document.getElementById('modalTitle').textContent = 'Asignar dieta';

      const heroContent = pendingDiet.foto_url
        ? `<img src="${pendingDiet.foto_url}" class="dp-hero-image">`
        : `<div class="dp-modal__preview-icon">${getDietIcon(pendingDiet.id_dieta)}</div>`;

      document.getElementById('modalPreview').innerHTML = `
        ${heroContent}
        <div>
          <div class="dp-modal__preview-name">${name}</div>
          <div class="dp-modal__preview-desc">${desc}</div>
        </div>`;

      const alreadyAssigned = assignedDiet && assignedDiet.id_dieta == pendingDiet.id_dieta;
      document.getElementById('modalInfo').innerHTML = alreadyAssigned
        ? `Esta dieta ya está asignada a <strong>${userName}</strong>. ¿Deseas mantenerla?`
        : `Se asignará el plan <strong>${name}</strong> a <strong>${userName}</strong>. La dieta anterior (si existe) será reemplazada.`;

      document.getElementById('dpModalOverlay').classList.add('dp-modal-overlay--visible');
    }

    function closeModal() {
      document.getElementById('dpModalOverlay').classList.remove('dp-modal-overlay--visible');
      pendingDiet = null;
    }

    function confirmAssign() {
      if (!pendingDiet || !currentUser) return;
      assignedDiet = { ...pendingDiet };
      closeModal();
      renderAssigned();
      renderCatalog(filteredDiets);
      saveDiet();
    }

    /* ══ GESTIÓN DEL CATÁLOGO DE DIETAS (CRUD) ══ */
    let currentEditDiet = null;
    let newDietPhotoBase64 = null;

    function openManageDietModal(dietId = null) {
      currentEditDiet = dietId ? allDiets.find(d => d.id_dieta == dietId) : null;
      newDietPhotoBase64 = null;

      document.getElementById('manageModalTitle').textContent = currentEditDiet ? 'Editar Dieta' : 'Nueva Dieta';
      document.getElementById('deleteDietBtn').style.display = currentEditDiet ? 'block' : 'none';

      document.getElementById('dietTypeInput').value = currentEditDiet ? (currentEditDiet.tipo || currentEditDiet.nombre || '') : '';
      document.getElementById('dietDescInput').value = currentEditDiet ? (currentEditDiet.descripcion || '') : '';
      document.getElementById('dietPhotoInput').value = '';

      const preview = document.getElementById('dietPhotoPreview');
      const placeholder = document.getElementById('dietPhotoPlaceholder');

      if (currentEditDiet && currentEditDiet.foto_url) {
        preview.src = currentEditDiet.foto_url;
        preview.style.display = 'block';
        placeholder.style.display = 'none';
      } else {
        preview.src = '';
        preview.style.display = 'none';
        placeholder.style.display = 'flex';
      }

      document.getElementById('manageDietModalOverlay').classList.add('visible');
    }

    function closeManageDietModal() {
      document.getElementById('manageDietModalOverlay').classList.remove('visible');
      currentEditDiet = null;
      newDietPhotoBase64 = null;
    }

    function previewDietPhoto(e) {
      const file = e.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = ev => {
        newDietPhotoBase64 = ev.target.result;
        const preview = document.getElementById('dietPhotoPreview');
        preview.src = newDietPhotoBase64;
        preview.style.display = 'block';
        document.getElementById('dietPhotoPlaceholder').style.display = 'none';
      };
      reader.readAsDataURL(file);
    }

    async function saveManageDiet() {
      const tipo = document.getElementById('dietTypeInput').value.trim();
      const descripcion = document.getElementById('dietDescInput').value.trim();

      if (!tipo) {
        alert('El nombre de la dieta es obligatorio.');
        return;
      }

      const payload = { tipo, descripcion };
      if (newDietPhotoBase64) payload.foto = newDietPhotoBase64;

      try {
        if (currentEditDiet) {
          await apiRequest(`dietas&id=${currentEditDiet.id_dieta}`, {
            method: 'PUT',
            body: JSON.stringify(payload),
          });
          // Update local
          Object.assign(currentEditDiet, payload);
          if (newDietPhotoBase64) currentEditDiet.foto_url = newDietPhotoBase64;
        } else {
          const res = await apiRequest('dietas', {
            method: 'POST',
            body: JSON.stringify(payload),
          });
          const newDiet = {
            id_dieta: res.id || Date.now(),
            tipo: payload.tipo,
            descripcion: payload.descripcion,
            foto_url: newDietPhotoBase64 || null
          };
          allDiets.unshift(newDiet);
        }

        filterDiets(document.getElementById('dietSearch').value);
        if (assignedDiet && currentEditDiet && assignedDiet.id_dieta == currentEditDiet.id_dieta) {
          assignedDiet = currentEditDiet;
          renderAssigned();
        }
        closeManageDietModal();
      } catch (e) {
        alert('Hubo un error al guardar la dieta.');
      }
    }

    async function deleteDiet() {
      if (!currentEditDiet) return;
      if (!confirm(`¿Estás seguro de eliminar la dieta "${currentEditDiet.tipo}"?`)) return;

      try {
        await apiRequest(`dietas&id=${currentEditDiet.id_dieta}`, {
          method: 'DELETE',
        });
        allDiets = allDiets.filter(d => d.id_dieta != currentEditDiet.id_dieta);
        filterDiets(document.getElementById('dietSearch').value);
        if (assignedDiet && assignedDiet.id_dieta == currentEditDiet.id_dieta) {
          assignedDiet = null;
          renderAssigned();
        }
        closeManageDietModal();
      } catch (e) {
        alert('No se pudo eliminar la dieta.');
      }
    }

    /* ══ GUARDAR ══ */
    async function saveDiet() {
      if (!currentUser) return;
      try {
        await apiRequest(`dieta_usuario&user_id=${encodeURIComponent(currentUser.id_usuario)}`, {
          method: 'POST',
          body: JSON.stringify({ id_dieta: assignedDiet ? assignedDiet.id_dieta : null }),
        });
      } catch (e) {
        console.warn('No se pudo guardar la dieta:', e.message);
        throw e;
      }
    }

    async function manualSaveDiet() {
      const btn = document.querySelector('.dp-save-btn');
      const originalHTML = btn.innerHTML;
      try {
        btn.innerHTML = `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg> Guardando...`;
        btn.style.opacity = '0.7';
        btn.style.pointerEvents = 'none';

        await saveDiet();

        btn.innerHTML = `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg> ¡Guardado!`;
        btn.style.background = '#10b981';
        btn.style.borderColor = '#10b981';

        setTimeout(() => {
          btn.innerHTML = originalHTML;
          btn.style.background = '';
          btn.style.borderColor = '';
          btn.style.opacity = '1';
          btn.style.pointerEvents = 'auto';
        }, 2000);
      } catch (e) {
        btn.innerHTML = originalHTML;
        btn.style.opacity = '1';
        btn.style.pointerEvents = 'auto';
        alert('Hubo un error al guardar los cambios.');
      }
    }





    /* ══ SIDEBAR MOBILE ══ */
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
    const menuBtn = document.getElementById('menuBtn');
    function updateMenuBtn() { menuBtn.style.display = window.innerWidth <= 900 ? 'block' : 'none'; }
    updateMenuBtn();
    window.addEventListener('resize', () => { updateMenuBtn(); if (window.innerWidth > 900) closeSidebar(); });

    /* ══ CLICK OUTSIDE ══ */
    document.addEventListener('click', e => {
      if (!e.target.closest('.search-wrap')) clearSearch();
      if (!e.target.closest('.dp-header-user')) {
        document.getElementById('dpUserPicker').style.display = 'none';
      }
    });
  </script>

</body>

</html>