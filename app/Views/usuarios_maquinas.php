<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Máquinas - HouseGYM</title>
  <link rel="stylesheet" href="assets/admin.css">
  <link rel="stylesheet" href="assets/usuarios_maquinas.css">
</head>

<body>

  <!-- Background glows -->
  <div class="bg-glow bg-glow--top-right"></div>
  <div class="bg-glow bg-glow--bottom-left"></div>

  <?php $current_page = 'maquinas'; include 'usuarios_sidebar.php'; ?>

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

      <div class="page-title">
        <h1>Catálogo de <span>Máquinas</span></h1>
        <p class="page-subtitle">Consulta las máquinas disponibles en el gimnasio</p>
      </div>

      <!-- TOOLBAR: solo búsqueda y filtro, sin botón agregar -->
      <div class="gm-toolbar">

        <div class="gm-search-wrap">
          <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <circle cx="11" cy="11" r="8" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35" />
          </svg>
          <input type="text" id="machineSearch" class="gm-search-input" placeholder="Buscar máquina..."
            oninput="filterMachines(this.value)" autocomplete="off">
          <button class="gm-search-clear" id="machineSearchClear" onclick="clearMachineSearch()">&times;</button>
        </div>

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

  <!-- MODAL: Vista detalle máquina (solo lectura) -->
  <div class="gm-modal-overlay" id="machineModal" onclick="handleOverlayClick(event)">
    <div class="gm-modal" id="machineModalInner">

      <div class="gm-modal__head">
        <div>
          <div class="gm-modal__title" id="modalTitle">Detalle de Máquina</div>
          <div class="gm-modal__subtitle" id="modalSubtitle"></div>
        </div>
        <button class="gm-modal__close" onclick="closeModal()" title="Cerrar">&times;</button>
      </div>

      <div class="gm-modal__body">

        <!-- Foto -->
        <div>
          <div class="gm-photo-zone gm-photo-zone--readonly" id="photoZone">
            <img id="photoPreview" src="" alt="foto máquina" style="display:none;">
            <div id="photoPlaceholder"
              style="display:flex;flex-direction:column;align-items:center;gap:8px;pointer-events:none;">
              <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <rect x="3" y="3" width="18" height="18" rx="2" />
                <circle cx="8.5" cy="8.5" r="1.5" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21" />
              </svg>
              <span class="gm-photo-zone__label">Sin foto</span>
            </div>
          </div>
        </div>

        <!-- Info (solo lectura) -->
        <div class="gm-form-side">

          <div class="gm-form-group">
            <label class="gm-form-label">Nombre</label>
            <div class="gm-detail-value" id="detailNombre">—</div>
          </div>

          <div class="gm-form-group">
            <label class="gm-form-label">Categoría</label>
            <div class="gm-detail-value" id="detailCategoria">—</div>
          </div>

          <div class="gm-form-group">
            <label class="gm-form-label">Descripción</label>
            <div class="gm-detail-value" id="detailDesc">—</div>
          </div>

          <div class="gm-form-group">
            <label class="gm-form-label">Piso / Ubicación</label>
            <div class="gm-detail-value" id="detailUbicacion">—</div>
          </div>

        </div>
      </div>

      <div class="gm-modal__foot">
        <button class="gm-btn gm-btn--ghost" onclick="closeModal()">Cerrar</button>
      </div>

    </div>
  </div>


  <script>
    /* ══════════════════════════════════════════════════
       HouseGYM — Usuarios: Catálogo de Máquinas
    ══════════════════════════════════════════════════ */

    const API_BASE = 'index.php?route=usuario_api&resource=';

    async function apiRequest(action, options = {}) {
      const defaults = { headers: { 'Content-Type': 'application/json' } };
      const res = await fetch(API_BASE + action, { ...defaults, ...options });
      if (res.status === 401) {
        window.location.href = 'index.php?route=login&error=no_autorizado';
        return null;
      }
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.json();
    }

    /* ── State ── */
    let allMachines = [];

    /* ── Sidebar móvil ── */
    function isMobileView() { return window.innerWidth <= 900; }
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
    function toggleSidebar() { document.getElementById('sidebar').classList.contains('sidebar--open') ? closeSidebar() : openSidebar(); }

    /* ══════════════════════════════
       LOAD MACHINES
    ══════════════════════════════ */
    async function loadMachines() {
      try {
        const data = await apiRequest('machines');
        if (!data) return;
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
          <div class="gm-card" onclick="openDetail(${m.id_maquina})">
            <div class="gm-card__photo">${photoHtml}</div>
            <div class="gm-card__name">${escHtml(m.nombre)}</div>
            ${m.categoria ? `<div class="gm-card__muscle">${escHtml(m.categoria)}</div>` : ''}
            ${m.ubicacion ? `<div class="gm-card__location"><svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>${escHtml(m.ubicacion)}</div>` : ''}
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
       MODAL DETALLE (solo lectura)
    ══════════════════════════════ */
    function openDetail(id) {
      const m = allMachines.find(x => x.id_maquina == id);
      if (!m) return;

      document.getElementById('modalTitle').textContent = m.nombre;
      document.getElementById('modalSubtitle').textContent = m.categoria || '';
      document.getElementById('detailNombre').textContent = m.nombre || '—';
      document.getElementById('detailCategoria').textContent = m.categoria || '—';
      document.getElementById('detailDesc').textContent = m.descripcion || '—';
      document.getElementById('detailUbicacion').textContent = m.ubicacion || '—';

      const preview = document.getElementById('photoPreview');
      const placeholder = document.getElementById('photoPlaceholder');
      if (m.foto) {
        preview.src = m.foto;
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
    }

    function handleOverlayClick(e) {
      if (e.target === document.getElementById('machineModal')) closeModal();
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    /* ── Escape HTML ── */
    function escHtml(str) {
      return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    /* ══════════════════════════════
       BOOT
    ══════════════════════════════ */
    async function boot() {
      try {
        const data = await apiRequest('profile');
        if (data && data.profile) {
          const name = data.profile.nombre || 'Usuario';
          const firstName = name.split(' ')[0];
          document.getElementById('topbarUserName').textContent = firstName;
          const parts = name.trim().split(' ');
          let ini = parts[0][0];
          if (parts.length > 1) ini += parts[1][0];
          document.getElementById('topbarAvatar').textContent = ini.toUpperCase();
        }
      } catch (e) { /* silent */ }
      loadMachines();
    }

    boot();

    window.openSidebar = openSidebar;
    window.closeSidebar = closeSidebar;
    window.toggleSidebar = toggleSidebar;
  </script>

</body>

</html>