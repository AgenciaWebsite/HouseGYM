<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dietas - HouseGYM</title>
  <link rel="stylesheet" href="assets/admin.css">
  <link rel="stylesheet" href="assets/admin_dieta.css">
</head>

<body>

  <!-- Background glows -->
  <div class="bg-glow bg-glow--top-right"></div>
  <div class="bg-glow bg-glow--bottom-left"></div>

  <?php $current_page = 'dietas';
  include 'admin_sidebar.php'; ?>

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
        <h1>Gestión de <span>Dietas</span></h1>
        <p class="page-subtitle">Agrega, edita y organiza los planes nutricionales</p>
      </div>

      <!-- TOOLBAR -->
      <div class="gd-toolbar">
        <div class="gd-search-wrap">
          <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <circle cx="11" cy="11" r="8" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35" />
          </svg>
          <input type="text" id="dietaSearch" class="gd-search-input" placeholder="Buscar dieta..."
            oninput="filterDietas(this.value)" autocomplete="off">
          <button class="gd-search-clear" id="dietaSearchClear" onclick="clearDietaSearch()">&times;</button>
        </div>

        <button class="gd-add-btn" onclick="openModal()">
          <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
          </svg>
          Agregar
        </button>
      </div><!-- /toolbar -->

      <!-- PANEL GRID -->
      <div class="gd-panel">

        <div class="gd-panel-header">
          <span class="gd-panel-title">Planes de Dieta</span>
          <span class="gd-panel-count" id="dietaCount">0 dietas</span>
        </div>

        <div class="gd-grid" id="dietaGrid">
          <div class="gd-empty">
            <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Cargando dietas...
          </div>
        </div>

      </div><!-- /panel -->

    </div><!-- /content -->
  </div><!-- /main-wrap -->


  <!-- ══════════════════════════════════════
       MODAL: Agregar / Editar Dieta
  ══════════════════════════════════════ -->
  <div class="gd-modal-overlay" id="dietaModal" onclick="handleOverlayClick(event)">
    <div class="gd-modal" id="dietaModalInner">

      <!-- Head -->
      <div class="gd-modal__head">
        <div>
          <div class="gd-modal__title" id="modalTitle">Agregar Dieta</div>
          <div class="gd-modal__subtitle" id="modalSubtitle">Nuevo plan nutricional</div>
        </div>
        <button class="gd-modal__close" onclick="closeModal()" title="Cerrar">&times;</button>
      </div>

      <!-- Body -->
      <div class="gd-modal__body">
        <div class="gd-form-side" style="width: 100%;">

          <div class="gd-form-group">
            <label class="gd-form-label" for="dietaName">Nombre / Tipo de la dieta</label>
            <input type="text" id="dietaName" class="gd-form-input" placeholder="Ej: Hipercalórica, Vegana...">
          </div>

          <div class="gd-form-group">
            <label class="gd-form-label" for="dietaDesc">Descripción del plan</label>
            <textarea id="dietaDesc" class="gd-form-textarea"
              placeholder="Descripción general, objetivos, y observaciones..."></textarea>
          </div>

        </div>
      </div><!-- /modal-body -->

      <!-- Footer -->
      <div class="gd-modal__foot">
        <div class="gd-feedback" id="modalFeedback"></div>
        <button class="gd-btn gd-btn--danger" id="deleteBtn" onclick="deleteDieta()" style="display:none;">
          <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <polyline points="3 6 5 6 21 6" />
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2" />
          </svg>
          Eliminar
        </button>
        <button class="gd-btn gd-btn--ghost" onclick="closeModal()">Cancelar</button>
        <button class="gd-btn gd-btn--primary" onclick="saveDieta()">
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
       HouseGYM — Admin Dietas
    ══════════════════════════════════════════════════ */

    const API_BASE = 'index.php?route=admin_api&resource=';

    async function apiRequest(action, options = {}) {
      const defaults = { headers: { 'Content-Type': 'application/json' } };
      const res = await fetch(API_BASE + action, { ...defaults, ...options });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.json();
    }

    /* ── State ── */
    let allDietas = [];
    let currentDieta = null;



    /* ══════════════════════════════
       LOAD DIETAS
    ══════════════════════════════ */
    async function loadDietas() {
      try {
        const data = await apiRequest('dietas');
        allDietas = data.dietas || [];
        renderGrid(allDietas);
      } catch (e) {
        document.getElementById('dietaGrid').innerHTML = `
          <div class="gd-empty">
            <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
              <circle cx="12" cy="12" r="10"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
            </svg>
            No se pudieron cargar las dietas.
          </div>`;
      }
    }

    /* ══════════════════════════════
       RENDER GRID
    ══════════════════════════════ */
    function renderGrid(dietas) {
      const grid = document.getElementById('dietaGrid');
      const count = document.getElementById('dietaCount');
      count.textContent = `${dietas.length} dieta${dietas.length !== 1 ? 's' : ''}`;

      if (!dietas.length) {
        grid.innerHTML = `
          <div class="gd-empty">
            <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
              <rect x="2" y="7" width="4" height="10" rx="1"/>
              <rect x="18" y="7" width="4" height="10" rx="1"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 10h12M6 14h12"/>
            </svg>
            Sin resultados para esta búsqueda.
          </div>`;
        return;
      }

      grid.innerHTML = dietas.map(d => {
        return `
          <div class="gd-card" onclick="openModal(${d.id_dieta})">
            <div class="gd-card__header">
              <div class="gd-card__icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
              </div>
              <div class="gd-card__name">${escHtml(d.tipo)}</div>
            </div>
            <div class="gd-card__desc">${escHtml(d.descripcion || 'Sin descripción detallada.')}</div>
          </div>`;
      }).join('');
    }

    /* ══════════════════════════════
       FILTER
    ══════════════════════════════ */
    function filterDietas(query) {
      const q = (query || '').toLowerCase().trim();
      const clearBtn = document.getElementById('dietaSearchClear');
      clearBtn.classList.toggle('visible', q.length > 0);

      const filtered = allDietas.filter(d => {
        return !q
          || d.tipo.toLowerCase().includes(q)
          || (d.descripcion || '').toLowerCase().includes(q);
      });
      renderGrid(filtered);
    }

    function clearDietaSearch() {
      document.getElementById('dietaSearch').value = '';
      filterDietas('');
    }

    /* ══════════════════════════════
       MODAL
    ══════════════════════════════ */
    function openModal(id = null) {
      currentDieta = id ? (allDietas.find(d => d.id_dieta == id) || null) : null;

      // Titles
      document.getElementById('modalTitle').textContent = currentDieta ? 'Editar Dieta' : 'Agregar Dieta';
      document.getElementById('modalSubtitle').textContent = currentDieta ? `Editando: ${currentDieta.tipo}` : 'Nuevo plan nutricional';

      // Delete button
      document.getElementById('deleteBtn').style.display = currentDieta ? 'flex' : 'none';

      // Reset form
      document.getElementById('dietaName').value = currentDieta ? currentDieta.tipo : '';
      document.getElementById('dietaDesc').value = currentDieta ? (currentDieta.descripcion || '') : '';
      document.getElementById('modalFeedback').style.display = 'none';

      document.getElementById('dietaModal').classList.add('visible');
      document.body.style.overflow = 'hidden';
    }

    function closeModal() {
      document.getElementById('dietaModal').classList.remove('visible');
      document.body.style.overflow = '';
      currentDieta = null;
    }

    function handleOverlayClick(e) {
      if (e.target === document.getElementById('dietaModal')) closeModal();
    }

    /* ── Save ── */
    async function saveDieta() {
      const tipo = document.getElementById('dietaName').value.trim();
      const descripcion = document.getElementById('dietaDesc').value.trim();

      if (!tipo) {
        document.getElementById('dietaName').style.borderColor = 'rgba(229,26,44,0.5)';
        showModalMsg('El nombre/tipo de la dieta es obligatorio.', true);
        return;
      }
      document.getElementById('dietaName').style.borderColor = '';

      const payload = { tipo, descripcion };

      try {
        if (currentDieta) {
          await apiRequest(`dietas&id=${encodeURIComponent(currentDieta.id_dieta)}`, {
            method: 'PUT',
            body: JSON.stringify(payload),
          });
          Object.assign(currentDieta, payload);
          allDietas = allDietas.map(d => d.id_dieta == currentDieta.id_dieta ? { ...d, ...payload } : d);
          showModalMsg('Dieta actualizada correctamente.');
        } else {
          const res = await apiRequest('dietas', {
            method: 'POST',
            body: JSON.stringify(payload),
          });
          const newDieta = { ...payload, id_dieta: res.id || Date.now() };
          allDietas.unshift(newDieta);
          showModalMsg('Dieta agregada correctamente.');
        }
        renderGrid(allDietas);
        setTimeout(closeModal, 1400);
      } catch (e) {
        showModalMsg('No se pudo guardar. Es posible que el tipo ya exista.', true);
      }
    }

    /* ── Delete ── */
    async function deleteDieta() {
      if (!currentDieta) return;
      if (!confirm(`¿Eliminar la dieta "${currentDieta.tipo}"? Esta acción no se puede deshacer y desvinculará a los usuarios asociados.`)) return;
      try {
        await apiRequest(`dietas&id=${encodeURIComponent(currentDieta.id_dieta)}`, { method: 'DELETE' });
        allDietas = allDietas.filter(d => d.id_dieta != currentDieta.id_dieta);
        renderGrid(allDietas);
        closeModal();
      } catch (e) {
        showModalMsg('No se pudo eliminar la dieta.', true);
      }
    }

    /* ── Modal feedback ── */
    function showModalMsg(text, isError = false) {
      const el = document.getElementById('modalFeedback');
      el.textContent = text;
      el.className = `gd-feedback ${isError ? 'gd-feedback--error' : 'gd-feedback--success'}`;
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
        loadDietas();
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