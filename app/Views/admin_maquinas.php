<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Máquinas - HouseGYM</title>
  <link rel="stylesheet" href="assets/admin.css">
  <link rel="stylesheet" href="assets/admin_maquinas.css">
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

      <div class="nav-item" onclick="window.location.href='index.php?route=admin'">
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

      <div class="nav-item nav-item--active">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <rect x="2" y="7" width="4" height="10" rx="1" />
          <rect x="18" y="7" width="4" height="10" rx="1" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 10h12M6 14h12" />
        </svg>
        Máquinas
      </div>

      <div class="nav-item" onclick="window.location.href='index.php?route=admin'">
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
        Cerrar Sesión
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

      <!-- Global search -->
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
        <h1>Gestión de <span>Máquinas</span></h1>
        <p class="page-subtitle">Agrega, edita y organiza las máquinas del gimnasio</p>
      </div>

      <!-- TOOLBAR -->
      <div class="gm-toolbar">

        <!-- Search de máquinas -->
        <div class="gm-search-wrap">
          <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <circle cx="11" cy="11" r="8" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35" />
          </svg>
          <input type="text" id="machineSearch" class="gm-search-input" placeholder="Buscar máquina..."
            oninput="filterMachines(this.value)" autocomplete="off">
          <button class="gm-search-clear" id="machineSearchClear" onclick="clearMachineSearch()">&times;</button>
        </div>

        <!-- Botón Agregar -->
        <button class="gm-add-btn" onclick="openModal()">
          <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
          </svg>
          Agregar
        </button>

        <!-- Filtro por grupo muscular -->
        <div class="gm-filter-wrap">
          <select class="gm-filter-select" id="muscleFilter" onchange="filterMachines(document.getElementById('machineSearch').value)">
            <option value="">Todas las categorías</option>
            <option value="Pecho">Pecho</option>
            <option value="Espalda">Espalda</option>
            <option value="Hombros">Hombros</option>
            <option value="Biceps">Bíceps</option>
            <option value="Triceps">Tríceps</option>
            <option value="Pierna">Pierna</option>
            <option value="Gluteos">Glúteos</option>
            <option value="Abdomen">Abdomen</option>
            <option value="Cardio">Cardio</option>
            <option value="Funcional">Funcional</option>
          </select>
          <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M7 10h10M11 16h2" />
          </svg>
        </div>

      </div><!-- /toolbar -->

      <!-- PANEL GRID -->
      <div class="gm-panel">

        <div class="gm-panel-header">
          <span class="gm-panel-title">Máquinas del Gimnasio</span>
          <span class="gm-panel-count" id="machineCount">0 máquinas</span>
        </div>

        <div class="gm-grid" id="machineGrid">
          <div class="gm-empty">
            <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
              <rect x="2" y="7" width="4" height="10" rx="1" />
              <rect x="18" y="7" width="4" height="10" rx="1" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 10h12M6 14h12" />
            </svg>
            Cargando máquinas...
          </div>
        </div>

      </div><!-- /panel -->

    </div><!-- /content -->
  </div><!-- /main-wrap -->


  <!-- ══════════════════════════════════════
       MODAL: Agregar / Editar Máquina
  ══════════════════════════════════════ -->
  <div class="gm-modal-overlay" id="machineModal" onclick="handleOverlayClick(event)">
    <div class="gm-modal" id="machineModalInner">

      <!-- Head -->
      <div class="gm-modal__head">
        <div>
          <div class="gm-modal__title" id="modalTitle">Agregar Máquina</div>
          <div class="gm-modal__subtitle" id="modalSubtitle">Nueva máquina al inventario</div>
        </div>
        <button class="gm-modal__close" onclick="closeModal()" title="Cerrar">&times;</button>
      </div>

      <!-- Body -->
      <div class="gm-modal__body">

        <!-- Foto -->
        <div>
          <div class="gm-photo-zone" id="photoZone" onclick="triggerFileInput()">
            <input type="file" id="photoInput" accept="image/*" onchange="previewPhoto(event)">

            <!-- Placeholder (visible sin imagen) -->
            <div id="photoPlaceholder" style="display:flex;flex-direction:column;align-items:center;gap:8px;pointer-events:none;">
              <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <rect x="3" y="3" width="18" height="18" rx="2" />
                <circle cx="8.5" cy="8.5" r="1.5" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21" />
              </svg>
              <span class="gm-photo-zone__label">Subir foto<br>de la máquina</span>
              <span class="gm-photo-zone__hint">JPG, PNG · Máx 5 MB</span>
            </div>

            <!-- Preview imagen -->
            <img id="photoPreview" src="" alt="preview" style="display:none;">
            <div class="gm-photo-zone__overlay" id="photoOverlay">
              <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.5-6.5a2.121 2.121 0 013 3L12 16H9v-3z" />
              </svg>
              Cambiar foto
            </div>
          </div>
        </div>

        <!-- Form fields -->
        <div class="gm-form-side">

          <div class="gm-form-group">
            <label class="gm-form-label" for="machineName">Nombre de la máquina</label>
            <input type="text" id="machineName" class="gm-form-input" placeholder="Ej: Prensa de piernas">
          </div>

          <div class="gm-form-group">
            <label class="gm-form-label" for="machineCategory">Grupo muscular / Categoría</label>
            <select id="machineCategory" class="gm-form-select">
              <option value="">Seleccionar categoría</option>
              <option value="Pecho">Pecho</option>
              <option value="Espalda">Espalda</option>
              <option value="Hombros">Hombros</option>
              <option value="Biceps">Bíceps</option>
              <option value="Triceps">Tríceps</option>
              <option value="Pierna">Pierna</option>
              <option value="Gluteos">Glúteos</option>
              <option value="Abdomen">Abdomen</option>
              <option value="Cardio">Cardio</option>
              <option value="Funcional">Funcional</option>
            </select>
          </div>

          <div class="gm-form-group">
            <label class="gm-form-label" for="machineDesc">Descripción</label>
            <textarea id="machineDesc" class="gm-form-textarea"
              placeholder="Descripción breve de la máquina y su uso..."></textarea>
          </div>

          <div class="gm-form-group">
            <label class="gm-form-label" for="machineLocation">Piso / Ubicación</label>
            <select id="machineLocation" class="gm-form-select">
              <option value="">Seleccionar piso</option>
              <option value="Piso 2">Piso 2</option>
              <option value="Piso 3">Piso 3</option>
              <option value="Piso 4">Piso 4</option>
            </select>
          </div>

        </div><!-- /form-side -->
      </div><!-- /modal-body -->

      <!-- Footer -->
      <div class="gm-modal__foot">
        <div class="gm-feedback" id="modalFeedback"></div>
        <button class="gm-btn gm-btn--danger" id="deleteBtn" onclick="deleteMachine()" style="display:none;">
          <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <polyline points="3 6 5 6 21 6"/>
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
          </svg>
          Eliminar
        </button>
        <button class="gm-btn gm-btn--ghost" onclick="closeModal()">Cancelar</button>
        <button class="gm-btn gm-btn--primary" onclick="saveMachine()">
          <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
          </svg>
          Guardar
        </button>
      </div>

    </div><!-- /modal -->
  </div><!-- /overlay -->


  <script>
    /* ══════════════════════════════════════════════════
       HouseGYM — Admin Máquinas
    ══════════════════════════════════════════════════ */

    const API_BASE = 'index.php?route=admin_api&resource=';

    async function apiRequest(action, options = {}) {
      const defaults = { headers: { 'Content-Type': 'application/json' } };
      const res = await fetch(API_BASE + action, { ...defaults, ...options });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.json();
    }

    /* ── State ── */
    let allMachines  = [];
    let currentMachine = null; // null = nueva máquina
    let photoBase64  = null;

    /* ══════════════════════════════
       SIDEBAR (reutilizado de admin)
    ══════════════════════════════ */
    function openSidebar()  { document.getElementById('sidebar').classList.add('sidebar--open'); document.getElementById('sidebarOverlay').classList.add('active'); }
    function closeSidebar() { document.getElementById('sidebar').classList.remove('sidebar--open'); document.getElementById('sidebarOverlay').classList.remove('active'); }
    function toggleSidebar() { document.getElementById('sidebar').classList.contains('sidebar--open') ? closeSidebar() : openSidebar(); }

    /* ══════════════════════════════
       LOAD MACHINES
    ══════════════════════════════ */
    async function loadMachines() {
      try {
        const data = await apiRequest('machines');
        allMachines = data.machines || [];
        renderGrid(allMachines);
      } catch (e) {
        document.getElementById('machineGrid').innerHTML = `
          <div class="gm-empty">
            <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
              <circle cx="12" cy="12" r="10"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
            </svg>
            No se pudieron cargar las máquinas.
          </div>`;
      }
    }

    /* ══════════════════════════════
       RENDER GRID
    ══════════════════════════════ */
    function renderGrid(machines) {
      const grid = document.getElementById('machineGrid');
      const count = document.getElementById('machineCount');
      count.textContent = `${machines.length} máquina${machines.length !== 1 ? 's' : ''}`;

      if (!machines.length) {
        grid.innerHTML = `
          <div class="gm-empty">
            <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
              <rect x="2" y="7" width="4" height="10" rx="1"/>
              <rect x="18" y="7" width="4" height="10" rx="1"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 10h12M6 14h12"/>
            </svg>
            Sin resultados para esta búsqueda.
          </div>`;
        return;
      }

      grid.innerHTML = machines.map(m => {
        const photoHtml = m.foto
          ? `<img src="${escHtml(m.foto)}" alt="${escHtml(m.nombre)}">`
          : `<div class="gm-card__photo--placeholder" style="display:flex;flex-direction:column;align-items:center;gap:6px;">
               <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                 <rect x="3" y="3" width="18" height="18" rx="2"/>
                 <circle cx="8.5" cy="8.5" r="1.5"/>
                 <path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21"/>
               </svg>
               <span style="font-size:10px;font-weight:600;color:var(--gray-dimmer);text-transform:uppercase;letter-spacing:.5px;">Foto</span>
             </div>`;

        return `
          <div class="gm-card" onclick="openModal(${m.id_maquina})">
            <div class="gm-card__photo">${photoHtml}</div>
            <div class="gm-card__name">${escHtml(m.nombre)}</div>
            ${m.categoria ? `<div class="gm-card__muscle">${escHtml(m.categoria)}</div>` : ''}
          </div>`;
      }).join('');
    }

    /* ══════════════════════════════
       FILTER
    ══════════════════════════════ */
    function filterMachines(query) {
      const q = (query || '').toLowerCase().trim();
      const cat = document.getElementById('muscleFilter').value;
      const clearBtn = document.getElementById('machineSearchClear');
      clearBtn.classList.toggle('visible', q.length > 0);

      const filtered = allMachines.filter(m => {
        const matchQ = !q
          || m.nombre.toLowerCase().includes(q)
          || (m.descripcion || '').toLowerCase().includes(q)
          || (m.ubicacion || '').toLowerCase().includes(q);
        const matchCat = !cat || (m.categoria || '') === cat;
        return matchQ && matchCat;
      });
      renderGrid(filtered);
    }

    function clearMachineSearch() {
      document.getElementById('machineSearch').value = '';
      filterMachines('');
    }

    /* ══════════════════════════════
       MODAL
    ══════════════════════════════ */
    function openModal(id = null) {
      currentMachine = id ? (allMachines.find(m => m.id_maquina == id) || null) : null;
      photoBase64 = null;

      // Titles
      document.getElementById('modalTitle').textContent    = currentMachine ? 'Editar Máquina' : 'Agregar Máquina';
      document.getElementById('modalSubtitle').textContent = currentMachine ? `Editando: ${currentMachine.nombre}` : 'Nueva máquina al inventario';

      // Delete button
      document.getElementById('deleteBtn').style.display = currentMachine ? 'flex' : 'none';

      // Reset form
      document.getElementById('machineName').value     = currentMachine ? currentMachine.nombre       : '';
      document.getElementById('machineCategory').value = currentMachine ? (currentMachine.categoria  || '') : '';
      document.getElementById('machineDesc').value     = currentMachine ? (currentMachine.descripcion || '') : '';
      document.getElementById('machineLocation').value = currentMachine ? (currentMachine.ubicacion   || '') : '';
      document.getElementById('photoInput').value      = '';
      document.getElementById('modalFeedback').style.display = 'none';

      // Photo
      const preview   = document.getElementById('photoPreview');
      const placeholder = document.getElementById('photoPlaceholder');
      if (currentMachine && currentMachine.foto) {
        preview.src = currentMachine.foto;
        preview.style.display = 'block';
        placeholder.style.display = 'none';
      } else {
        preview.src = '';
        preview.style.display = 'none';
        placeholder.style.display = 'flex';
      }

      document.getElementById('machineModal').classList.add('visible');
      document.body.style.overflow = 'hidden';
    }

    function closeModal() {
      document.getElementById('machineModal').classList.remove('visible');
      document.body.style.overflow = '';
      currentMachine = null;
      photoBase64    = null;
    }

    function handleOverlayClick(e) {
      if (e.target === document.getElementById('machineModal')) closeModal();
    }

    /* ── Photo preview ── */
    function triggerFileInput() {
      // Let the native input handle clicks; this is just a fallback.
    }

    function previewPhoto(e) {
      const file = e.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = ev => {
        photoBase64 = ev.target.result;
        const preview = document.getElementById('photoPreview');
        preview.src = photoBase64;
        preview.style.display = 'block';
        document.getElementById('photoPlaceholder').style.display = 'none';
      };
      reader.readAsDataURL(file);
    }

    /* ── Save ── */
    async function saveMachine() {
      const nombre     = document.getElementById('machineName').value.trim();
      const categoria  = document.getElementById('machineCategory').value;
      const descripcion = document.getElementById('machineDesc').value.trim();
      const ubicacion  = document.getElementById('machineLocation').value.trim();

      if (!nombre) {
        document.getElementById('machineName').style.borderColor = 'rgba(229,26,44,0.5)';
        showModalMsg('El nombre de la máquina es obligatorio.', true);
        return;
      }
      document.getElementById('machineName').style.borderColor = '';

      const payload = { nombre, categoria, descripcion, ubicacion };
      if (photoBase64) payload.foto = photoBase64;

      try {
        if (currentMachine) {
          await apiRequest(`machines&id=${encodeURIComponent(currentMachine.id_maquina)}`, {
            method: 'PUT',
            body: JSON.stringify(payload),
          });
          Object.assign(currentMachine, payload);
          allMachines = allMachines.map(m => m.id_maquina == currentMachine.id_maquina ? { ...m, ...payload } : m);
          showModalMsg('Máquina actualizada correctamente.');
        } else {
          const res = await apiRequest('machines', {
            method: 'POST',
            body: JSON.stringify(payload),
          });
          const newMachine = res.machine || { ...payload, id_maquina: res.id || Date.now() };
          allMachines.unshift(newMachine);
          showModalMsg('Máquina agregada correctamente.');
        }
        renderGrid(allMachines);
        setTimeout(closeModal, 1400);
      } catch (e) {
        showModalMsg('No se pudo guardar. Intenta de nuevo.', true);
      }
    }

    /* ── Delete ── */
    async function deleteMachine() {
      if (!currentMachine) return;
      if (!confirm(`¿Eliminar la máquina "${currentMachine.nombre}"? Esta acción no se puede deshacer.`)) return;
      try {
        await apiRequest(`machines&id=${encodeURIComponent(currentMachine.id_maquina)}`, { method: 'DELETE' });
        allMachines = allMachines.filter(m => m.id_maquina != currentMachine.id_maquina);
        renderGrid(allMachines);
        closeModal();
      } catch (e) {
        showModalMsg('No se pudo eliminar la máquina.', true);
      }
    }

    /* ── Modal feedback ── */
    function showModalMsg(text, isError = false) {
      const el = document.getElementById('modalFeedback');
      el.textContent = text;
      el.className   = `gm-feedback ${isError ? 'gm-feedback--error' : 'gm-feedback--success'}`;
      el.style.display = 'block';
      if (!isError) setTimeout(() => { el.style.display = 'none'; }, 3000);
    }

    /* ══════════════════════════════
       GLOBAL SEARCH (topbar)
    ══════════════════════════════ */
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
    });

    /* ── Escape HTML ── */
    function escHtml(str) {
      return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    /* ── ESC closes modal ── */
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') closeModal();
    });

    /* ══════════════════════════════
       BOOT
    ══════════════════════════════ */
    apiRequest('session')
      .then(data => {
        if (data && data.admin) {
          const el = document.getElementById('adminUserLabel');
          if (el) el.textContent = data.admin.usuario;
        }
        loadMachines();
      })
      .catch(() => {
        window.location.href = 'index.php?route=login&error=no_autorizado';
      });

    window.openSidebar  = openSidebar;
    window.closeSidebar = closeSidebar;
    window.toggleSidebar = toggleSidebar;
    window.handleSearch = handleSearch;
    window.clearSearch  = clearSearch;
  </script>

</body>
</html>
