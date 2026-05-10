<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ejercicios - HouseGYM</title>
  <link rel="stylesheet" href="assets/admin/dashboard.css">
  <link rel="stylesheet" href="assets/usuarios/ejercicios.css">
</head>

<body>

  <!-- Background glows -->
  <div class="bg-glow bg-glow--top-right"></div>
  <div class="bg-glow bg-glow--bottom-left"></div>

  <?php $current_page = 'ejercicios'; include 'sidebar.php'; ?>

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
        <h1>Catálogo de <span>Ejercicios</span></h1>
        <p class="page-subtitle">Consulta los ejercicios disponibles en el gimnasio</p>
      </div>

      <!-- TOOLBAR: solo búsqueda y filtro, sin botón agregar -->
      <div class="gm-toolbar">

        <div class="gm-search-wrap">
          <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <circle cx="11" cy="11" r="8" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35" />
          </svg>
          <input type="text" id="ejercicioSearch" class="gm-search-input" placeholder="Buscar ejercicio..."
            oninput="filterEjercicios(this.value)" autocomplete="off">
          <button class="gm-search-clear" id="ejercicioSearchClear" onclick="clearEjercicioSearch()">&times;</button>
        </div>

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

  <!-- MODAL: DETALLE EJERCICIO -->
  <div id="exerciseModal" class="ex-modal" onclick="closeExModal(event)">
    <div class="ex-modal__card" onclick="event.stopPropagation()">
      <button class="ex-modal__close" onclick="closeExModal()">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <div class="ex-modal__header">
        <div id="modalExPhoto" class="ex-modal__photo"></div>
      </div>

      <div class="ex-modal__body">
        <div class="ex-modal__meta">
          <span id="modalExMuscleTop" class="ex-modal__muscle"></span>
        </div>
        <h2 id="modalExName" class="ex-modal__name"></h2>
        
        <div class="ex-modal__stats">
          <div class="ex-modal__stat">
            <span class="ex-modal__stat-val" id="modalExMuscle">--</span>
            <span class="ex-modal__stat-label">Músculo</span>
          </div>
          <div id="modalExMachineWrap" style="display:contents;">
            <div class="ex-modal__stat-sep"></div>
            <div class="ex-modal__stat">
              <span class="ex-modal__stat-val" id="modalExMachine">--</span>
              <span class="ex-modal__stat-label">Máquina</span>
            </div>
          </div>
        </div>

        <div class="ex-modal__description">
          <label>Instrucciones / Descripción</label>
          <p id="modalExDesc">No hay descripción disponible para este ejercicio.</p>
        </div>
      </div>
    </div>
  </div>


  <script>
    /* ══════════════════════════════════════════════════
       HouseGYM — Usuarios: Catálogo de Ejercicios
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
    let allEjercicios = [];
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
       LOAD DATA
    ══════════════════════════════ */
    async function loadEjercicios() {
      try {
        const [dataEjercicios, dataMachines] = await Promise.all([
          apiRequest('ejercicios'),
          apiRequest('machines')
        ]);
        
        if (dataMachines && dataMachines.machines) {
          allMachines = dataMachines.machines;
        }

        if (!dataEjercicios) return;
        allEjercicios = dataEjercicios.ejercicios || [];
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
          <div class="gm-card" onclick="openDetail(${m.id_ejercicio})">
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
       MODAL DETALLE (solo lectura)
    ══════════════════════════════ */
    function openDetail(id) {
      const m = allEjercicios.find(x => x.id_ejercicio == id);
      if (!m) return;

      const modal = document.getElementById('exerciseModal');
      const photo = document.getElementById('modalExPhoto');
      
      if (m.imagen_url) {
        photo.style.backgroundImage = `url('${m.imagen_url}')`;
        photo.innerHTML = '';
      } else {
        photo.style.backgroundImage = 'none';
        photo.innerHTML = `
          <svg width="60" height="60" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/>
          </svg>`;
      }

      document.getElementById('modalExName').textContent      = m.nombre;
      document.getElementById('modalExMuscleTop').textContent = m.grupo_muscular || 'General';
      document.getElementById('modalExMuscle').textContent    = m.grupo_muscular || 'General';
      document.getElementById('modalExDesc').textContent      = m.descripcion || 'No hay descripción disponible para este ejercicio.';

      const machWrap = document.getElementById('modalExMachineWrap');
      const machVal  = document.getElementById('modalExMachine');
      
      if (m.id_maquina) {
        const maquina = allMachines.find(mac => mac.id_maquina == m.id_maquina);
        machVal.textContent = maquina ? maquina.nombre : '—';
        machWrap.style.display = 'contents';
      } else {
        machWrap.style.display = 'none';
      }

      modal.classList.add('ex-modal--active');
      document.body.style.overflow = 'hidden';
    }

    function closeExModal() {
      document.getElementById('exerciseModal').classList.remove('ex-modal--active');
      document.body.style.overflow = '';
    }

    function handleOverlayClick(e) {
      if (e.target.id === 'exerciseModal') closeExModal();
    }

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
      loadEjercicios();
    }

    boot();

    window.openSidebar = openSidebar;
    window.closeSidebar = closeSidebar;
    window.toggleSidebar = toggleSidebar;
  </script>

</body>

</html>