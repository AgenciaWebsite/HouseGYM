<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Usuarios - HouseGYM</title>
  <link rel="stylesheet" href="assets/admin/dashboard.css">
  <link rel="stylesheet" href="assets/admin/usuarios.css">
</head>

<body>

  <!-- Background glows -->
  <div class="bg-glow bg-glow--top-right"></div>
  <div class="bg-glow bg-glow--bottom-left"></div>

  <?php $current_page = 'usuarios';
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

      <!-- Search global (reutilizando clases de admin.css) -->
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
        <h1>Gestión de <span>Usuarios</span></h1>
        <p class="page-subtitle">Administra, edita y controla accesos de cada usuario</p>
      </div>

      <!-- Search + Results area -->
      <div class="gu-layout">

        <!-- LEFT: search & user list -->
        <div class="gu-panel gu-panel--list">

          <div class="gu-search-bar">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
              <circle cx="11" cy="11" r="8" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35" />
            </svg>
            <input type="text" id="userSearch" class="gu-search-input" placeholder="Buscar usuario..."
              oninput="filterUsers(this.value)" autocomplete="off">
            <button class="gu-search-clear" id="userSearchClear" onclick="clearUserSearch()" style="display:none">
              &times;
            </button>
          </div>

          <div class="gu-list-header">
            <span>Resultados</span>
            <span class="gu-list-count" id="userCount">0 usuarios</span>
          </div>

          <div class="gu-user-list" id="userList">
            <div class="gu-empty">Cargando usuarios...</div>
          </div>

        </div><!-- /left -->

        <!-- RIGHT: user detail / edit panel -->
        <div class="gu-panel gu-panel--detail" id="detailPanel">

          <!-- Default state (nothing selected) -->
          <div class="gu-detail-empty" id="detailEmpty">
            <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
            </svg>
            <p>Selecciona un usuario<br>para ver su detalle</p>
          </div>

          <!-- Detail card (shown when user is selected) -->
          <div class="gu-detail-card" id="detailCard" style="display:none">

            <!-- User header -->
            <div class="gu-detail-header">
              <div class="gu-detail-avatar" id="detailAvatar">--</div>
              <div>
                <div class="gu-detail-name" id="detailName">--</div>
                <div class="gu-detail-cedula" id="detailCedula">CC --</div>
              </div>
              <div class="gu-detail-badges" id="detailBadges"></div>
            </div>

            <!-- Fields -->
            <div class="gu-fields">
              <div class="gu-field-row">
                <span class="gu-field-label">Nombre</span>
                <input type="text" class="gu-field-input" id="editNombre" placeholder="Nombre usuario">
              </div>
              <div class="gu-field-row">
                <span class="gu-field-label">Documento</span>
                <input type="text" inputmode="numeric" class="gu-field-input" id="editCedula" placeholder="#documento"
                  oninput="this.value = this.value.replace(/\D/g, '')">
              </div>
              <div class="gu-field-row">
                <span class="gu-field-label">Contraseña actual</span>
                <input type="text" class="gu-field-input" id="currentPassword" readonly
                  style="background-color: var(--black-bg); color: var(--white); cursor: not-allowed;"
                  placeholder="Sin contraseña">
              </div>
              <div class="gu-field-row">
                <span class="gu-field-label">Nueva Contraseña</span>
                <input type="password" class="gu-field-input" id="editPassword" placeholder="Escribe para cambiarla">
              </div>
            </div>

            <!-- Toggles -->
            <div class="gu-toggles">
              <div class="gu-toggle-row" id="toggleRowPersonalizado">
                <div class="gu-toggle-info">
                  <span class="gu-toggle-label">Personalizado</span>
                  <span class="gu-toggle-sub">Rutina de pago activa</span>
                </div>
                <label class="toggle-switch">
                  <input type="checkbox" id="editRutina">
                  <span class="toggle-switch__track"></span>
                </label>
              </div>
              <div>

                <!-- Toggles -->

                <div class="gu-toggle-row" id="toggleRowDieta">
                  <div class="gu-toggle-info">
                    <span class="gu-toggle-label">Dieta</span>
                    <span class="gu-toggle-sub">Acceso a plan de dieta</span>
                  </div>
                  <label class="toggle-switch">
                    <input type="checkbox" id="editDieta">
                    <span class="toggle-switch__track"></span>
                  </label>
                </div>
              </div>

              <!-- Actions -->
              <div class="gu-detail-actions">
                <button class="btn btn--ghost" onclick="cancelEdit()">Cancelar</button>
                <button class="btn btn--primary" onclick="saveUser()">
                  <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                  </svg>
                  Guardar
                </button>
              </div>

              <div class="feedback-msg" id="detailFeedback"></div>

            </div><!-- /gu-detail-card -->

          </div><!-- /right -->

        </div><!-- /gu-layout -->

      </div><!-- /content -->
    </div><!-- /main-wrap -->

    <script>


      /* ─── API ─── */
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

      /* ─── State ─── */
      let allUsers = [];
      let currentUser = null;

      /* ─── Load users ─── */
      async function loadUsers() {
        try {
          const data = await apiRequest('users');
          allUsers = data.users || [];
          renderUserList(allUsers);
        } catch (e) {
          document.getElementById('userList').innerHTML = '<div class="gu-empty">No se pudo cargar la lista.</div>';
        }
      }

      function renderUserList(list) {
        const el = document.getElementById('userList');
        document.getElementById('userCount').textContent = `${list.length} usuario${list.length !== 1 ? 's' : ''}`;
        if (!list.length) {
          el.innerHTML = '<div class="gu-empty">Sin resultados</div>';
          return;
        }
        el.innerHTML = list.map(u => {
          const name = u.nombre || `Usuario ${u.id_usuario}`;
          const initials = name.split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase();
          const isActive = Number(u.activo) === 1;
          const hasRutina = Number(u.plan_personalizado) === 1;
          const hasDieta = Number(u.dieta) === 1;
          return `
          <div class="gu-user-row ${currentUser && currentUser.id_usuario == u.id_usuario ? 'gu-user-row--active' : ''}"
               onclick="selectUser(${u.id_usuario})">
            <div class="gu-user-row__left">
              <div class="gu-user-avatar">${initials}</div>
              <div>
                <div class="gu-user-name">${name}</div>
                <div class="gu-user-doc">CC ${u.cedula || '--'}</div>
              </div>
            </div>
            <div class="gu-user-row__right">
              ${hasRutina ? '<span class="badge badge--rutina">Rutina</span>' : ''}
              ${hasDieta ? '<span class="badge badge--dieta">Dieta</span>' : ''}
              ${!isActive ? '<span class="badge badge--neutral">Inactivo</span>' : ''}
            </div>
          </div>`;
        }).join('');
      }

      /* ─── Filter ─── */
      function filterUsers(q) {
        const clearBtn = document.getElementById('userSearchClear');
        clearBtn.style.display = q.trim() ? 'flex' : 'none';
        const filtered = q.trim().length < 1
          ? allUsers
          : allUsers.filter(u => {
            const name = (u.nombre || '').toLowerCase();
            const doc = String(u.cedula || '');
            return name.includes(q.toLowerCase()) || doc.includes(q);
          });
        renderUserList(filtered);
      }
      function clearUserSearch() {
        document.getElementById('userSearch').value = '';
        document.getElementById('userSearchClear').style.display = 'none';
        renderUserList(allUsers);
      }

      /* ─── Select user ─── */
      function selectUser(id) {
        currentUser = allUsers.find(u => u.id_usuario == id) || null;
        if (!currentUser) return;
        renderUserList(allUsers); // re-render to highlight active row
        showDetail(currentUser);
      }

      function showDetail(u) {
        document.getElementById('detailEmpty').style.display = 'none';
        document.getElementById('detailCard').style.display = 'flex';

        const name = u.nombre || `Usuario ${u.id_usuario}`;
        const initials = name.split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase();
        const hasRutina = Number(u.plan_personalizado) === 1;
        const hasDieta = Number(u.dieta) === 1;

        document.getElementById('detailAvatar').textContent = initials;
        document.getElementById('detailName').textContent = name;
        document.getElementById('detailCedula').textContent = `CC ${u.cedula || '--'}`;

        const badges = [];
        if (Number(u.activo) === 1) badges.push('<span class="badge badge--active">Activo</span>');
        else badges.push('<span class="badge badge--neutral">Inactivo</span>');
        if (hasRutina) badges.push('<span class="badge badge--rutina">Rutina</span>');
        if (hasDieta) badges.push('<span class="badge badge--dieta">Dieta</span>');
        document.getElementById('detailBadges').innerHTML = badges.join('');

        document.getElementById('editNombre').value = u.nombre || '';
        document.getElementById('editCedula').value = u.cedula || '';
        document.getElementById('currentPassword').value = u.contrasena || '';
        document.getElementById('editPassword').value = '';
        document.getElementById('editRutina').checked = hasRutina;
        document.getElementById('editDieta').checked = hasDieta;

        // Subpanels (Removed as they don't exist in this view)
        // toggleSubDieta(hasDieta, false);
        // toggleSubRutina(hasRutina, false);


        document.getElementById('detailFeedback').style.display = 'none';
      }

      function cancelEdit() {
        currentUser = null;
        document.getElementById('detailEmpty').style.display = 'flex';
        document.getElementById('detailCard').style.display = 'none';
        renderUserList(allUsers);
      }

      /* ─── Subpanels ─── */
      function toggleSubDieta(active, updateCheck = true) {
        const body = document.getElementById('subDietaBody');
        const panel = document.getElementById('subpanelDieta');
        panel.style.display = 'block';
        body.style.opacity = active ? '1' : '0.4';
        body.style.pointerEvents = active ? 'auto' : 'none';
        if (updateCheck) document.getElementById('subDietaToggle').checked = active;
        if (updateCheck) document.getElementById('editDieta').checked = active;
      }

      function toggleSubRutina(active, updateCheck = true) {
        const body = document.getElementById('subRutinaBody');
        const overlay = document.getElementById('rutinaBlurOverlay');
        const panel = document.getElementById('subpanelRutina');
        panel.style.display = 'block';
        overlay.style.display = active ? 'none' : 'flex';
        body.style.filter = active ? 'none' : 'blur(4px)';
        body.style.pointerEvents = active ? 'auto' : 'none';
        if (updateCheck) document.getElementById('subRutinaToggle').checked = active;
        if (updateCheck) document.getElementById('editRutina').checked = active;
      }

      /* ─── Day lists (Dieta) ─── */
      function renderDietaDays(days) {
        const container = document.getElementById('dietaDayList');
        if (!days.length) { container.innerHTML = ''; return; }
        container.innerHTML = days.map((d, i) => `
        <div class="gu-day-card">
          <div class="gu-day-label">Día ${i + 1}</div>
          <div class="gu-day-body">
            <div class="gu-day-pdf">
              <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              <span>pdf</span>
            </div>
            <div class="gu-day-info">
              <div class="gu-day-title">${d.nombre || 'Nombre de la dieta'}</div>
              <div class="gu-day-desc">${d.descripcion || 'descripcion'}</div>
            </div>
            <button class="gu-remove-btn" onclick="removeDietaDay(${i})">
              <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 9l-6 6M9 9l6 6"/>
              </svg>
            </button>
          </div>
        </div>`).join('');
      }

      function addDietaDay() {
        if (!currentUser) return;
        const dias = currentUser.dieta_dias || [];
        currentUser.dieta_dias = [...dias, { nombre: 'Nuevo plan', descripcion: 'descripcion' }];
        renderDietaDays(currentUser.dieta_dias);
      }
      function removeDietaDay(i) {
        if (!currentUser || !currentUser.dieta_dias) return;
        currentUser.dieta_dias.splice(i, 1);
        renderDietaDays(currentUser.dieta_dias);
      }

      /* ─── Day lists (Rutina) ─── */
      function renderRutinaDays(days) {
        const container = document.getElementById('rutinaDayList');
        if (!days.length) { container.innerHTML = ''; return; }
        container.innerHTML = days.map((d, i) => `
        <div class="gu-day-card">
          <div class="gu-day-label">Día ${i + 1}</div>
          <div class="gu-day-body">
            <div class="gu-day-img">
              <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <rect x="3" y="3" width="18" height="18" rx="2"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l4-4 4 4 4-4 4 4"/>
              </svg>
              <span>imagen</span>
            </div>
            <div class="gu-day-info">
              <div class="gu-day-title-sm">${d.dias || 'dias'}</div>
              <div class="gu-day-desc">${d.descripcion || '"descripcion del día"'}</div>
              <div class="gu-day-muscle">${d.grupo_muscular || 'grupo muscular'}</div>
            </div>
            <button class="gu-remove-btn" onclick="removeRutinaDay(${i})">
              <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 9l-6 6M9 9l6 6"/>
              </svg>
            </button>
          </div>
        </div>`).join('');
      }

      function addRutinaDay() {
        if (!currentUser) return;
        const dias = currentUser.rutina_dias || [];
        currentUser.rutina_dias = [...dias, { dias: 'Nuevo día', descripcion: '"descripcion del día"', grupo_muscular: 'grupo muscular' }];
        renderRutinaDays(currentUser.rutina_dias);
      }
      function removeRutinaDay(i) {
        if (!currentUser || !currentUser.rutina_dias) return;
        currentUser.rutina_dias.splice(i, 1);
        renderRutinaDays(currentUser.rutina_dias);
      }

      /* ─── Save ─── */
      async function saveUser() {
        if (!currentUser) return;
        const nombre = document.getElementById('editNombre').value.trim();
        const cedula = document.getElementById('editCedula').value.trim();
        const password = document.getElementById('editPassword').value;
        const rutina = document.getElementById('editRutina').checked ? 1 : 0;
        const dieta = document.getElementById('editDieta').checked ? 1 : 0;

        if (!nombre || !cedula) {
          showDetailMsg('Nombre y documento son obligatorios.', true);
          return;
        }

        const payload = { nombre, cedula, plan_personalizado: rutina, dieta };
        if (password) payload.contrasena = password;

        try {
          await apiRequest(`users&id=${encodeURIComponent(currentUser.id_usuario)}`, {
            method: 'PUT',
            body: JSON.stringify(payload),
          });
          // Update local state
          Object.assign(currentUser, { nombre, cedula, plan_personalizado: rutina, dieta });
          if (password) currentUser.contrasena = password;
          allUsers = allUsers.map(u => u.id_usuario == currentUser.id_usuario ? { ...u, ...currentUser } : u);
          showDetail(currentUser);
          renderUserList(allUsers);
          showDetailMsg('Cambios guardados correctamente.');
        } catch (e) {
          showDetailMsg('No se pudo guardar los cambios.', true);
        }
      }

      function showDetailMsg(text, isError = false) {
        const el = document.getElementById('detailFeedback');
        el.textContent = text;
        el.style.display = 'block';
        el.className = `feedback-msg ${isError ? 'feedback-msg--error' : 'feedback-msg--success'}`;
        setTimeout(() => el.style.display = 'none', 3500);
      }

      /* ─── Global search (topbar) ─── */
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

      /* ─── Boot ─── */
      apiRequest('session')
        .then(data => {
          if (data && data.admin) {
            const el = document.getElementById('adminUserLabel');
            if (el) el.textContent = data.admin.usuario;
          }
          loadUsers();
        })
        .catch(() => {
          window.location.href = 'index.php?route=login&error=no_autorizado';
        });
    </script>

</body>

</html>