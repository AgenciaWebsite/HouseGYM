<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi Rutina - HouseGYM</title>
  <link rel="stylesheet" href="assets/admin.css">
  <link rel="stylesheet" href="assets/usuarios.css">
  <link rel="stylesheet" href="assets/usuarios_rutina.css">
</head>

<body>

  <div class="bg-glow bg-glow--top-right"></div>
  <div class="bg-glow bg-glow--bottom-left"></div>

  <?php $current_page = 'rutina'; include 'usuarios_sidebar.php'; ?>

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

      <div class="topbar-user">
        <div class="topbar-user__dot"></div>
        <span class="topbar-user__name" id="topbarUserName">Cargando...</span>
        <div class="topbar-user__avatar" id="topbarAvatar">U</div>
      </div>
    </div>

    <!-- CONTENT -->
    <div class="content">

      <div class="page-title">
        <h1>Mi <span>Rutina</span></h1>
        <p class="page-subtitle">Selecciona un día para ver los ejercicios asignados.</p>
      </div>

      <!-- Empty state -->
      <div id="routineStateMsg" class="routine-empty-state" style="display:none;">
        <div class="empty-icon">
          <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h3>Sin rutina asignada</h3>
        <p>Aún no tienes una rutina personalizada. Acércate a recepción para que tu entrenador te asigne una.</p>
      </div>

      <!-- Two-panel layout -->
      <div id="routineLayout" class="routine-layout" style="display:none;">

        <!-- LEFT: Days list -->
        <div class="days-panel">
          <div class="days-panel__header">
            <h3>Días de Entrenamiento</h3>
          </div>
          <div class="days-list" id="daysList">
            <!-- Populated by JS -->
          </div>
        </div>

        <!-- RIGHT: Exercises panel -->
        <div class="exercises-panel" id="exercisesPanel">
          <div class="exercises-panel__header">
            <h3>Ejercicios</h3>
            <span class="day-tag" id="dayTag" style="display:none;"></span>
          </div>
          <div class="exercises-panel__body" id="exercisesBody">
            <div class="exercises-placeholder">
              <svg width="38" height="38" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zM12 2.25V4.5m5.834.166l-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243l-1.59-1.59" />
              </svg>
              <p>Selecciona un día para ver los ejercicios</p>
            </div>
          </div>
        </div>

      </div><!-- /routineLayout -->

    </div><!-- /content -->
  </div><!-- /main-wrap -->

  <script>


    /* ══════════════════════════════════════
       API HELPERS
    ══════════════════════════════════════ */
    const API_URL = 'index.php?route=usuario_api&resource=';

    async function fetchApi(resource) {
      try {
        const response = await fetch(API_URL + resource);
        if (response.status === 401) {
          window.location.href = 'index.php?route=login&error=no_autorizado';
          return null;
        }
        const data = await response.json();
        if (!data.ok) throw new Error(data.error);
        return data;
      } catch (error) {
        console.error('Error fetching ' + resource, error);
        return null;
      }
    }

    /* ══════════════════════════════════════
       HELPERS
    ══════════════════════════════════════ */
    function setInitials(name) {
      const parts = name.trim().split(' ');
      let ini = parts[0][0];
      if (parts.length > 1) ini += parts[1][0];
      document.getElementById('topbarAvatar').textContent = ini.toUpperCase();
    }

    /* ══════════════════════════════════════
       BUILD DAY ROWS
    ══════════════════════════════════════ */
    const MAX_PILLS = 6;
    let _routineData = [];
    let _activeDay   = -1;

    function buildDaysList(routine) {
      const list = document.getElementById('daysList');
      list.innerHTML = routine.map((dia, i) => {
        const n      = dia.ejercicios ? dia.ejercicios.length : 0;
        const isRest = n === 0;
        let pills    = '';
        for (let p = 0; p < MAX_PILLS; p++) {
          pills += `<div class="day-pill${p < n ? ' day-pill--filled' : ''}"></div>`;
        }
        return `
          <div class="day-row${isRest ? ' day-row--rest' : ''}" id="dr-${i}" onclick="selectDay(${i})">
            <div class="day-row__label">
              <div class="day-check">
                <svg width="9" height="9" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="3.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
              </div>
              <span>Día ${i + 1}</span>
            </div>
            <div class="day-row__pills">${pills}</div>
          </div>`;
      }).join('');
    }

    /* ══════════════════════════════════════
       SELECT DAY
    ══════════════════════════════════════ */
    const NO_IMG_ICON = `
      <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/>
      </svg>`;

    function selectDay(index) {
      if (_activeDay >= 0) {
        document.getElementById('dr-' + _activeDay)?.classList.remove('day-row--active');
      }
      _activeDay = index;
      document.getElementById('dr-' + index).classList.add('day-row--active');

      const tag = document.getElementById('dayTag');
      tag.textContent   = 'Día ' + (index + 1);
      tag.style.display = 'inline-block';

      const body = document.getElementById('exercisesBody');
      const dia  = _routineData[index];
      const n    = dia.ejercicios ? dia.ejercicios.length : 0;

      if (n === 0) {
        body.innerHTML = `
          <div class="exercises-rest">
            <svg width="34" height="34" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="opacity:.22">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
            <p>Día de descanso</p>
          </div>`;
        return;
      }

      const cards = dia.ejercicios.map(ej => {
        const hasImg = ej.imagen_url && ej.imagen_url.trim() !== '';
        return `
          <div class="ex-card">
            <div class="ex-card__photo"${hasImg ? ` style="background-image:url('${ej.imagen_url}')"` : ''}>
              ${hasImg ? '' : NO_IMG_ICON}
              <div class="ex-card__stats"><b>${ej.series}</b>×${ej.reps}</div>
            </div>
            <div class="ex-card__name">${ej.nombre}</div>
            <div class="ex-card__muscle">${ej.grupo_muscular || ''}</div>
          </div>`;
      }).join('');

      body.innerHTML = `<div class="exercises-grid">${cards}</div>`;

      if (window.innerWidth <= 860) {
        document.getElementById('exercisesPanel').scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    }

    /* ══════════════════════════════════════
       INIT
    ══════════════════════════════════════ */
    async function init() {
      const dataProfile = await fetchApi('profile');
      if (dataProfile && dataProfile.profile) {
        const firstName = dataProfile.profile.nombre.split(' ')[0];
        document.getElementById('topbarUserName').textContent = firstName;
        setInitials(dataProfile.profile.nombre);
      }

      const dataRoutine = await fetchApi('routine');

      if (!dataRoutine || !dataRoutine.routine || dataRoutine.routine.length === 0) {
        document.getElementById('routineStateMsg').style.display = 'flex';
        document.getElementById('routineLayout').style.display   = 'none';
        return;
      }

      _routineData = dataRoutine.routine;
      buildDaysList(_routineData);

      document.getElementById('routineStateMsg').style.display = 'none';
      document.getElementById('routineLayout').style.display   = 'grid';

      if (window.innerWidth > 860) {
        const first = _routineData.findIndex(d => d.ejercicios && d.ejercicios.length > 0);
        if (first >= 0) selectDay(first);
      }
    }

    init();
  </script>
</body>

</html>
