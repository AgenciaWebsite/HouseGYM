<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Máquinas - HouseGYM</title>
  <link rel="stylesheet" href="assets/admin/dashboard.css">
  <link rel="stylesheet" href="assets/admin/maquinas.css">
</head>

<body>

  <!-- Background glows -->
  <div class="bg-glow bg-glow--top-right"></div>
  <div class="bg-glow bg-glow--bottom-left"></div>

  <?php $current_page = 'maquinas';
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
          <select class="gm-filter-select" id="muscleFilter"
            onchange="filterMachines(document.getElementById('machineSearch').value)">
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
          <div class="gm-photo-zone" id="photoZone">
            <!-- Input invisible que cubre toda la zona (pointer-events se manejan por JS) -->
            <input type="file" id="photoInput" accept="image/*" onchange="previewPhoto(event)"
              style="position:absolute;inset:0;width:100%;height:100%;opacity:0;cursor:pointer;z-index:5;">

            <!-- Placeholder (visible sin imagen) -->
            <div id="photoPlaceholder"
              style="display:flex;flex-direction:column;align-items:center;gap:8px;pointer-events:none;">
              <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <rect x="3" y="3" width="18" height="18" rx="2" />
                <circle cx="8.5" cy="8.5" r="1.5" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21" />
              </svg>
              <span class="gm-photo-zone__label">Subir foto<br>de la máquina</span>
              <span class="gm-photo-zone__hint">JPG, PNG · Máx 10 MB</span>
            </div>

            <!-- Preview imagen -->
            <img id="photoPreview" src="" alt="preview" style="display:none;pointer-events:none;">
            <div class="gm-photo-zone__overlay" id="photoOverlay" style="pointer-events:none;">
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
            <polyline points="3 6 5 6 21 6" />
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2" />
          </svg>
          Eliminar
        </button>
        <button class="gm-btn gm-btn--ghost" onclick="closeModal()">Cancelar</button>
        <button class="gm-btn gm-btn--primary" id="saveBtn" onclick="saveMachine()">
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

    /* ── Petición multipart (para subir fotos) ── */
    async function apiFormRequest(action, formData) {
      // NO poner Content-Type: el navegador lo pone con el boundary correcto
      const res = await fetch(API_BASE + action, {
        method: 'POST',
        body: formData,
      });
      // Intentar leer JSON siempre (incluso en errores 4xx/5xx)
      let data;
      try { data = await res.json(); } catch { data = {}; }
      if (!res.ok || data.ok === false) {
        const err = new Error(data.msg || `HTTP ${res.status}`);
        err.serverData = data;
        throw err;
      }
      return data;
    }

    /* ── State ── */
    let allMachines = [];
    let currentMachine = null; // null = nueva máquina
    let photoFile = null;      // File object seleccionado por el usuario



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
      photoFile = null;

      // Titles
      document.getElementById('modalTitle').textContent = currentMachine ? 'Editar Máquina' : 'Agregar Máquina';
      document.getElementById('modalSubtitle').textContent = currentMachine ? `Editando: ${currentMachine.nombre}` : 'Nueva máquina al inventario';

      // Delete button
      document.getElementById('deleteBtn').style.display = currentMachine ? 'flex' : 'none';

      // Reset form
      document.getElementById('machineName').value = currentMachine ? currentMachine.nombre : '';
      document.getElementById('machineCategory').value = currentMachine ? (currentMachine.categoria || '') : '';
      document.getElementById('machineDesc').value = currentMachine ? (currentMachine.descripcion || '') : '';
      document.getElementById('machineLocation').value = currentMachine ? (currentMachine.ubicacion || '') : '';
      document.getElementById('photoInput').value = '';
      document.getElementById('modalFeedback').style.display = 'none';

      // Photo
      const preview = document.getElementById('photoPreview');
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
      photoFile = null;
    }

    function handleOverlayClick(e) {
      if (e.target === document.getElementById('machineModal')) closeModal();
    }

    /* ── Photo preview ── */
    function triggerFileInput() {
      // Forzar apertura del selector de archivos del sistema
      document.getElementById('photoInput').click();
    }

    function previewPhoto(e) {
      const file = e.target.files[0];
      if (!file) return;
      // Guardar el File object para enviarlo como multipart
      photoFile = file;
      // Mostrar preview usando URL.createObjectURL (más eficiente que base64)
      const preview = document.getElementById('photoPreview');
      if (preview._objectUrl) URL.revokeObjectURL(preview._objectUrl);
      const objectUrl = URL.createObjectURL(file);
      preview._objectUrl = objectUrl;
      preview.src = objectUrl;
      preview.style.display = 'block';
      document.getElementById('photoPlaceholder').style.display = 'none';
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

      // Construir FormData para enviar como multipart (necesario para el archivo)
      const fd = new FormData();
      fd.append('nombre',      nombre);
      fd.append('categoria',   categoria);
      fd.append('descripcion', descripcion);
      fd.append('ubicacion',   ubicacion);
      if (photoFile) {
        fd.append('foto', photoFile);
      }

      // Deshabilitar botón mientras se procesa (puede tardar por Cloudinary)
      const saveBtn = document.getElementById('saveBtn');
      saveBtn.disabled = true;
      saveBtn.innerHTML = '<svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg> Guardando...';

      try {
        let res;
        if (currentMachine) {
          // Editar: usar _method=PUT con POST multipart
          fd.append('_method', 'PUT');
          res = await apiFormRequest(
            `machines&id=${encodeURIComponent(currentMachine.id_maquina)}`,
            fd
          );
          // Actualizar el objeto local con los datos nuevos
          const fotoActualizada = res.foto_url ?? (photoFile ? null : currentMachine.foto);
          Object.assign(currentMachine, { nombre, categoria, descripcion, ubicacion });
          if (fotoActualizada !== null) currentMachine.foto = fotoActualizada;
          allMachines = allMachines.map(m =>
            m.id_maquina == currentMachine.id_maquina ? { ...m, ...currentMachine } : m
          );
          showModalMsg('Máquina actualizada correctamente.');
        } else {
          res = await apiFormRequest('machines', fd);
          const newMachine = {
            id_maquina: res.id || Date.now(),
            nombre, categoria, descripcion, ubicacion,
            foto: res.foto_url || null,
          };
          allMachines.unshift(newMachine);
          showModalMsg('Máquina agregada correctamente.');
        }
        renderGrid(allMachines);
        setTimeout(closeModal, 1400);
      } catch (e) {
        // Mostrar mensaje específico del servidor (ej. tamaño de imagen, credenciales, etc.)
        const msg = e.message && e.message !== 'Failed to fetch'
          ? e.message
          : 'No se pudo guardar. Intenta de nuevo.';
        showModalMsg(msg, true);
      } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Guardar';
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
      el.className = `gm-feedback ${isError ? 'gm-feedback--error' : 'gm-feedback--success'}`;
      el.style.display = 'block';
      if (!isError) setTimeout(() => { el.style.display = 'none'; }, 3000);
    }



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
        loadMachines();
      })
      .catch(() => {
        window.location.href = 'index.php?route=login&error=no_autorizado';
      });

    window.openSidebar = openSidebar;
    window.closeSidebar = closeSidebar;
    window.toggleSidebar = toggleSidebar;
  </script>

</body>

</html>