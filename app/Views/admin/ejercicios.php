<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ejercicios - HouseGYM</title>
  <link rel="stylesheet" href="assets/admin/dashboard.css">
  <link rel="stylesheet" href="assets/admin/ejercicios.css">
</head>

<body>

  <!-- Background glows -->
  <div class="bg-glow bg-glow--top-right"></div>
  <div class="bg-glow bg-glow--bottom-left"></div>

  <?php $current_page = 'ejercicios';
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
        <h1>Gestión de <span>Ejercicios</span></h1>
        <p class="page-subtitle">Agrega, edita y organiza los ejercicios del gimnasio</p>
      </div>

      <!-- TOOLBAR -->
      <div class="gm-toolbar">

        <!-- Search de ejercicios -->
        <div class="gm-search-wrap">
          <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <circle cx="11" cy="11" r="8" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35" />
          </svg>
          <input type="text" id="ejercicioSearch" class="gm-search-input" placeholder="Buscar ejercicio..."
            oninput="filterEjercicios(this.value)" autocomplete="off">
          <button class="gm-search-clear" id="ejercicioSearchClear" onclick="clearEjercicioSearch()">&times;</button>
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
          <select class="gm-filter-select" id="muscleFilter"
            onchange="filterEjercicios(document.getElementById('ejercicioSearch').value)">
            <option value="">Todos los grupos</option>
            <option value="Pierna">Pierna</option>
            <option value="Pecho">Pecho</option>
            <option value="Espalda">Espalda</option>
            <option value="Brazo">Brazo</option>
            <option value="Cardio">Cardio</option>
            <option value="Abdomen">Abdomen</option>
          </select>
          <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M7 10h10M11 16h2" />
          </svg>
        </div>

      </div><!-- /toolbar -->

      <!-- PANEL GRID -->
      <div class="gm-panel">

        <div class="gm-panel-header">
          <span class="gm-panel-title">Ejercicios del Gimnasio</span>
          <span class="gm-panel-count" id="ejercicioCount">0 ejercicios</span>
        </div>

        <div class="gm-grid" id="ejercicioGrid">
          <div class="gm-empty">
            <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
              <rect x="2" y="7" width="4" height="10" rx="1" />
              <rect x="18" y="7" width="4" height="10" rx="1" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 10h12M6 14h12" />
            </svg>
            Cargando ejercicios...
          </div>
        </div>

      </div><!-- /panel -->

    </div><!-- /content -->
  </div><!-- /main-wrap -->


  <!-- ══════════════════════════════════════
       MODAL: Agregar / Editar Ejercicio
  ══════════════════════════════════════ -->
  <div class="gm-modal-overlay" id="ejercicioModal" onclick="handleOverlayClick(event)">
    <div class="gm-modal" id="ejercicioModalInner">

      <!-- Head -->
      <div class="gm-modal__head">
        <div>
          <div class="gm-modal__title" id="modalTitle">Agregar Ejercicio</div>
          <div class="gm-modal__subtitle" id="modalSubtitle">Nuevo ejercicio al inventario</div>
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
            <div id="photoPlaceholder"
              style="display:flex;flex-direction:column;align-items:center;gap:8px;pointer-events:none;">
              <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <rect x="3" y="3" width="18" height="18" rx="2" />
                <circle cx="8.5" cy="8.5" r="1.5" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21" />
              </svg>
              <span class="gm-photo-zone__label">Subir foto<br>del ejercicio</span>
              <span class="gm-photo-zone__hint">JPG, PNG · Máx 5 MB</span>
            </div>

            <!-- Preview imagen -->
            <img id="photoPreview" src="" alt="preview" style="display:none;">
            <div class="gm-photo-zone__overlay" id="photoOverlay">
              <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15.232 5.232l3.536 3.536M9 13l6.5-6.5a2.121 2.121 0 013 3L12 16H9v-3z" />
              </svg>
              Cambiar foto
            </div>
          </div>
        </div>

        <!-- Form fields -->
        <div class="gm-form-side">

          <div class="gm-form-group">
            <label class="gm-form-label" for="ejercicioName">Nombre del ejercicio</label>
            <input type="text" id="ejercicioName" class="gm-form-input" placeholder="Ej: Press de banca">
          </div>

          <div class="gm-form-group">
            <label class="gm-form-label" for="ejercicioGrupo">Grupo Muscular <span
                style="color:#e51a2c;">*</span></label>
            <select id="ejercicioGrupo" class="gm-form-select">
              <option value="">Seleccionar grupo</option>
              <option value="1">Pierna</option>
              <option value="2">Pecho</option>
              <option value="3">Espalda</option>
              <option value="4">Brazo</option>
              <option value="5">Cardio</option>
              <option value="6">Abdomen</option>
            </select>
          </div>

          <div class="gm-form-group">
            <label class="gm-form-label" for="ejercicioDesc">Descripción</label>
            <textarea id="ejercicioDesc" class="gm-form-textarea"
              placeholder="Descripción breve del ejercicio..."></textarea>
          </div>

          <div class="gm-form-group">
            <label class="gm-form-label" for="ejercicioMaquina">Máquina asignada (Opcional)</label>
            <div class="gm-search-select-wrap" id="maquinaSelectWrap">
              <input type="hidden" id="ejercicioMaquina" value="">
              <div class="gm-search-select-trigger" onclick="toggleMaquinaDropdown(event)">
                <span id="maquinaSelectedLabel">Ninguna / Peso Libre</span>
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
              </div>
              <div class="gm-search-select-dropdown">
                <div class="gm-search-select-search-wrap">
                  <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <circle cx="11" cy="11" r="8" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35" />
                  </svg>
                  <input type="text" class="gm-search-select-search-input" id="maquinaSearchInput"
                    placeholder="Buscar máquina..." oninput="filterMaquinaOptions(this.value)" autocomplete="off">
                </div>
                <div class="gm-search-select-options" id="maquinaOptionsList">
                  <!-- Opciones se cargan dinámicamente -->
                </div>
              </div>
            </div>
          </div>

        </div><!-- /form-side -->
      </div><!-- /modal-body -->

      <!-- Footer -->
      <div class="gm-modal__foot">
        <div class="gm-feedback" id="modalFeedback"></div>
        <button class="gm-btn gm-btn--danger" id="deleteBtn" onclick="deleteEjercicio()" style="display:none;">
          <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <polyline points="3 6 5 6 21 6" />
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2" />
          </svg>
          Eliminar
        </button>
        <button class="gm-btn gm-btn--ghost" onclick="closeModal()">Cancelar</button>
        <button class="gm-btn gm-btn--primary" onclick="saveEjercicio()">
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
       HouseGYM — Admin Ejercicios
    ══════════════════════════════════════════════════ */

    const API_BASE = 'index.php?route=admin_api&resource=';

    async function apiRequest(action, options = {}) {
      const defaults = { headers: { 'Content-Type': 'application/json' } };
      const res = await fetch(API_BASE + action, { ...defaults, ...options });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.json();
    }

    /* ── State ── */
    let allEjercicios = [];
    let currentEjercicio = null; // null = nuevo ejercicio
    let photoBase64 = null;
    let allMachines = [];



    /* ══════════════════════════════
       LOAD DATA
    ══════════════════════════════ */
    async function loadEjercicios() {
      try {
        const data = await apiRequest('ejercicios');
        allEjercicios = data.ejercicios || [];
        renderGrid(allEjercicios);
      } catch (e) {
        document.getElementById('ejercicioGrid').innerHTML = `
          <div class="gm-empty">
            <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
              <circle cx="12" cy="12" r="10"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
            </svg>
            No se pudieron cargar los ejercicios.
          </div>`;
      }
    }

    async function loadMachinesSelect() {
      try {
        const data = await apiRequest('machines');
        allMachines = data.machines || [];
        renderMaquinaOptions();
      } catch (e) {
        console.error("Error al cargar máquinas", e);
      }
    }

    function renderMaquinaOptions(machines = allMachines) {
      const list = document.getElementById('maquinaOptionsList');
      const currentValue = document.getElementById('ejercicioMaquina').value;

      let html = `<div class="gm-search-select-option ${!currentValue ? 'gm-search-select-option--selected' : ''}" 
                  onclick="selectMaquina('', 'Ninguna / Peso Libre')">Ninguna / Peso Libre</div>`;

      if (machines.length === 0 && allMachines.length > 0) {
        html += `<div class="gm-search-select-option--empty">No se encontraron máquinas</div>`;
      } else {
        html += machines.map(m => `
          <div class="gm-search-select-option ${currentValue == m.id_maquina ? 'gm-search-select-option--selected' : ''}" 
               onclick="selectMaquina('${m.id_maquina}', '${escHtml(m.nombre)}')">
            ${escHtml(m.nombre)}
          </div>
        `).join('');
      }

      list.innerHTML = html;
    }

    function toggleMaquinaDropdown(e) {
      e.stopPropagation();
      const wrap = document.getElementById('maquinaSelectWrap');
      const isActive = wrap.classList.contains('active');

      // Cerrar si estaba abierto, o abrir y enfocar input
      if (isActive) {
        closeMaquinaDropdown();
      } else {
        wrap.classList.add('active');
        const input = document.getElementById('maquinaSearchInput');
        input.value = '';
        renderMaquinaOptions();
        setTimeout(() => input.focus(), 50);
      }
    }

    function closeMaquinaDropdown() {
      document.getElementById('maquinaSelectWrap').classList.remove('active');
    }

    function filterMaquinaOptions(query) {
      const q = query.toLowerCase().trim();
      const filtered = allMachines.filter(m => m.nombre.toLowerCase().includes(q));
      renderMaquinaOptions(filtered);
    }

    function selectMaquina(id, name) {
      document.getElementById('ejercicioMaquina').value = id;
      document.getElementById('maquinaSelectedLabel').textContent = name;
      closeMaquinaDropdown();
    }

    // Cerrar dropdown al hacer click fuera
    document.addEventListener('click', (e) => {
      if (!e.target.closest('.gm-search-select-wrap')) {
        closeMaquinaDropdown();
      }
    });

    /* ══════════════════════════════
       RENDER GRID
    ══════════════════════════════ */
    function renderGrid(ejercicios) {
      const grid = document.getElementById('ejercicioGrid');
      const count = document.getElementById('ejercicioCount');
      count.textContent = `${ejercicios.length} ejercicio${ejercicios.length !== 1 ? 's' : ''}`;

      if (!ejercicios.length) {
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

      grid.innerHTML = ejercicios.map(m => {
        const photoHtml = m.imagen_url
          ? `<img src="${escHtml(m.imagen_url)}" alt="${escHtml(m.nombre)}">`
          : `<div class="gm-card__photo--placeholder" style="display:flex;flex-direction:column;align-items:center;gap:6px;">
               <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                 <rect x="3" y="3" width="18" height="18" rx="2"/>
                 <circle cx="8.5" cy="8.5" r="1.5"/>
                 <path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21"/>
               </svg>
               <span style="font-size:10px;font-weight:600;color:var(--gray-dimmer);text-transform:uppercase;letter-spacing:.5px;">Foto</span>
             </div>`;

        return `
          <div class="gm-card" onclick="openModal(${m.id_ejercicio})">
            <div class="gm-card__photo">${photoHtml}</div>
            <div class="gm-card__name">${escHtml(m.nombre)}</div>
            ${m.grupo_muscular ? `<div class="gm-card__muscle">${escHtml(m.grupo_muscular)}</div>` : ''}
          </div>`;
      }).join('');
    }

    /* ══════════════════════════════
       FILTER
    ══════════════════════════════ */
    function filterEjercicios(query) {
      const q = (query || '').toLowerCase().trim();
      const cat = document.getElementById('muscleFilter').value;
      const clearBtn = document.getElementById('ejercicioSearchClear');
      clearBtn.classList.toggle('visible', q.length > 0);

      const filtered = allEjercicios.filter(m => {
        const matchQ = !q
          || m.nombre.toLowerCase().includes(q)
          || (m.descripcion || '').toLowerCase().includes(q);
        const matchCat = !cat || (m.grupo_muscular || '') === cat;
        return matchQ && matchCat;
      });
      renderGrid(filtered);
    }

    function clearEjercicioSearch() {
      document.getElementById('ejercicioSearch').value = '';
      filterEjercicios('');
    }

    /* ══════════════════════════════
       MODAL
    ══════════════════════════════ */
    function openModal(id = null) {
      currentEjercicio = id ? (allEjercicios.find(m => m.id_ejercicio == id) || null) : null;
      photoBase64 = null;

      // Titles
      document.getElementById('modalTitle').textContent = currentEjercicio ? 'Editar Ejercicio' : 'Agregar Ejercicio';
      document.getElementById('modalSubtitle').textContent = currentEjercicio ? `Editando: ${currentEjercicio.nombre}` : 'Nuevo ejercicio al inventario';

      // Delete button
      document.getElementById('deleteBtn').style.display = currentEjercicio ? 'flex' : 'none';

      // Reset form
      document.getElementById('ejercicioName').value = currentEjercicio ? currentEjercicio.nombre : '';
      document.getElementById('ejercicioGrupo').value = currentEjercicio ? (currentEjercicio.id_grupo || '') : '';
      document.getElementById('ejercicioDesc').value = currentEjercicio ? (currentEjercicio.descripcion || '') : '';

      // Reset searchable machine select
      const machineId = currentEjercicio ? (currentEjercicio.id_maquina || '') : '';
      document.getElementById('ejercicioMaquina').value = machineId;
      const machine = allMachines.find(m => m.id_maquina == machineId);
      document.getElementById('maquinaSelectedLabel').textContent = machine ? machine.nombre : 'Ninguna / Peso Libre';
      closeMaquinaDropdown();

      document.getElementById('photoInput').value = '';
      document.getElementById('modalFeedback').style.display = 'none';

      // Photo
      const preview = document.getElementById('photoPreview');
      const placeholder = document.getElementById('photoPlaceholder');
      if (currentEjercicio && currentEjercicio.imagen_url) {
        preview.src = currentEjercicio.imagen_url;
        preview.style.display = 'block';
        placeholder.style.display = 'none';
      } else {
        preview.src = '';
        preview.style.display = 'none';
        placeholder.style.display = 'flex';
      }

      document.getElementById('ejercicioModal').classList.add('visible');
      document.body.style.overflow = 'hidden';
    }

    function closeModal() {
      document.getElementById('ejercicioModal').classList.remove('visible');
      document.body.style.overflow = '';
      currentEjercicio = null;
      photoBase64 = null;
    }

    function handleOverlayClick(e) {
      if (e.target === document.getElementById('ejercicioModal')) closeModal();
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
    async function saveEjercicio() {
      const nombre = document.getElementById('ejercicioName').value.trim();
      const id_grupo = document.getElementById('ejercicioGrupo').value;
      const descripcion = document.getElementById('ejercicioDesc').value.trim();
      const id_maquina = document.getElementById('ejercicioMaquina').value;

      if (!nombre || !id_grupo) {
        document.getElementById('ejercicioName').style.borderColor = !nombre ? 'rgba(229,26,44,0.5)' : '';
        document.getElementById('ejercicioGrupo').style.borderColor = !id_grupo ? 'rgba(229,26,44,0.5)' : '';
        showModalMsg('El nombre y grupo muscular son obligatorios.', true);
        return;
      }
      document.getElementById('ejercicioName').style.borderColor = '';
      document.getElementById('ejercicioGrupo').style.borderColor = '';

      const payload = { nombre, id_grupo, descripcion, id_maquina };
      if (photoBase64) payload.foto = photoBase64;

      try {
        if (currentEjercicio) {
          await apiRequest(`ejercicios&id=${encodeURIComponent(currentEjercicio.id_ejercicio)}`, {
            method: 'PUT',
            body: JSON.stringify(payload),
          });

          showModalMsg('Ejercicio actualizado correctamente.');
        } else {
          await apiRequest('ejercicios', {
            method: 'POST',
            body: JSON.stringify(payload),
          });
          showModalMsg('Ejercicio agregado correctamente.');
        }

        // Recargar la lista de ejercicios para asegurar datos frescos
        loadEjercicios();

        setTimeout(closeModal, 1400);
      } catch (e) {
        showModalMsg('No se pudo guardar. Intenta de nuevo.', true);
      }
    }

    /* ── Delete ── */
    async function deleteEjercicio() {
      if (!currentEjercicio) return;
      if (!confirm(`¿Eliminar el ejercicio "${currentEjercicio.nombre}"? Esta acción no se puede deshacer.`)) return;
      try {
        await apiRequest(`ejercicios&id=${encodeURIComponent(currentEjercicio.id_ejercicio)}`, { method: 'DELETE' });
        allEjercicios = allEjercicios.filter(m => m.id_ejercicio != currentEjercicio.id_ejercicio);
        renderGrid(allEjercicios);
        closeModal();
      } catch (e) {
        showModalMsg('No se pudo eliminar el ejercicio.', true);
      }
    }

    /* ── Modal feedback ── */
    function showModalMsg(text, isError = false) {
      const el = document.getElementById('modalFeedback');
      el.textContent = text;
      el.className = `gm-feedback ${isError ? 'gm-feedback--error' : 'gm-feedback--success'}`;
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
      return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
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
        loadEjercicios();
        loadMachinesSelect();
      })
      .catch(() => {
        window.location.href = 'index.php?route=login&error=no_autorizado';
      });

    window.openSidebar = openSidebar;
    window.closeSidebar = closeSidebar;
    window.toggleSidebar = toggleSidebar;
    window.handleSearch = handleSearch;
    window.clearSearch = clearSearch;
  </script>

</body>

</html>