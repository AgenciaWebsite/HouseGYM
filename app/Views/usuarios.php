<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="theme-color" content="#0a0a0a">
  <title>Rutina — HouseGYM</title>
  <link rel="stylesheet" href="assets/usuarios.css">
</head>

<body>

  <!-- Glow de fondo -->
  <div class="ru-glow ru-glow--top"></div>
  <div class="ru-glow ru-glow--bot"></div>

  <!-- ══════════════════════════════════
       APP SHELL
  ══════════════════════════════════ -->
  <div class="ru-app">

    <!-- HEADER -->
    <header class="ru-header">
      <div class="ru-header__titles">
        <span class="ru-header__label">rutina global</span>
        <span class="ru-header__name">Nuestra Rutina</span>
        <div class="ru-header__line"></div>
      </div>
      <button class="ru-menu-btn" onclick="openDrawer()" aria-label="Menú">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </header>

    <!-- MAIN CONTENT -->
    <main class="ru-main" id="mainContent">

      <!-- Loading state -->
      <div class="ru-loading" id="loadingState">
        <div class="ru-spinner"></div>
        <span>Cargando rutina...</span>
      </div>

      <!-- Content (hidden until loaded) -->
      <div id="rutinaContent" style="display:none; display:flex; flex-direction:column; gap:20px;">

        <!-- WEEK TABS -->
        <div class="ru-week-scroll">
          <div class="ru-week-tabs" id="weekTabs">
            <!-- Rendered by JS -->
          </div>
        </div>

        <!-- DAY CAROUSEL + NAV -->
        <div class="ru-day-wrap">

          <!-- Dot indicators -->
          <div class="ru-day-dots" id="dayDots"></div>

          <!-- Cards carousel -->
          <div class="ru-carousel">
            <div class="ru-carousel-track" id="carouselTrack">
              <!-- Rendered by JS -->
            </div>
          </div>

          <!-- Arrow navigation -->
          <div class="ru-nav">
            <button class="ru-nav__btn" id="prevBtn" onclick="prevDay()" aria-label="Día anterior">
              <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
              </svg>
            </button>
            <span class="ru-nav__label" id="navLabel">Día 1</span>
            <button class="ru-nav__btn" id="nextBtn" onclick="nextDay()" aria-label="Día siguiente">
              <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6" />
              </svg>
            </button>
          </div>

        </div><!-- /day-wrap -->

      </div><!-- /rutinaContent -->

    </main><!-- /main -->

    <!-- BOTTOM BAR DOTS -->
    <div class="ru-bottom-bar" id="bottomBar" style="display:none;">
      <div class="ru-bottom-bar__dot ru-bottom-bar__dot--active" id="bb1"></div>
      <div class="ru-bottom-bar__dot" id="bb2"></div>
      <div class="ru-bottom-bar__dot" id="bb3"></div>
      <div class="ru-bottom-bar__dot" id="bb4"></div>
    </div>

  </div><!-- /app -->


  <!-- ══════════════════════════════════
       DRAWER MENÚ
  ══════════════════════════════════ -->
  <div class="ru-drawer-overlay" id="drawerOverlay" onclick="closeDrawer()"></div>
  <div class="ru-drawer" id="drawer">

    <div class="ru-drawer__head">
      <div class="ru-drawer__logo">
        <div class="ru-drawer__icon">
          <svg width="16" height="16" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
          </svg>
        </div>
        <span class="ru-drawer__brand">HouseGYM</span>
      </div>
      <button class="ru-drawer__close" onclick="closeDrawer()">&times;</button>
    </div>

    <!-- User info -->
    <div class="ru-drawer__user">
      <div class="ru-drawer__avatar" id="drawerAvatar">U</div>
      <div>
        <div class="ru-drawer__username" id="drawerName">Usuario</div>
        <div class="ru-drawer__cedula" id="drawerCedula">CC —</div>
      </div>
    </div>

    <nav class="ru-drawer__nav">
      <div class="ru-drawer__section">Mi Plan</div>

      <a class="ru-drawer__item ru-drawer__item--active" href="index.php?route=rutina_global">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        Rutina Global
      </a>

      <a class="ru-drawer__item" href="index.php?route=mi_rutina">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
        </svg>
        Mi Rutina
      </a>

      <a class="ru-drawer__item" href="index.php?route=mi_dieta">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
        </svg>
        Mi Dieta
      </a>

      <div class="ru-drawer__section">Gimnasio</div>

      <a class="ru-drawer__item" href="index.php?route=maquinas">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <rect x="2" y="7" width="4" height="10" rx="1" />
          <rect x="18" y="7" width="4" height="10" rx="1" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 10h12M6 14h12" />
        </svg>
        Máquinas
      </a>
    </nav>

    <div class="ru-drawer__foot">
      <a class="ru-drawer__logout" href="index.php?route=logout">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        Cerrar Sesión
      </a>
    </div>
  </div>


  <!-- ══════════════════════════════════
       POP-UP EJERCICIO
  ══════════════════════════════════ -->
  <div class="ru-popup-overlay" id="popupOverlay" onclick="handlePopupOverlayClick(event)">
    <div class="ru-popup" id="popupSheet">

      <div class="ru-popup__handle"><span></span></div>

      <!-- Header del ejercicio -->
      <div class="ru-popup__head">
        <div class="ru-popup__head-left">
          <div class="ru-popup__num" id="popNum">1</div>
          <div>
            <div class="ru-popup__exercise-name" id="popName">Press Plano Barra</div>
            <div class="ru-popup__machine-desc" id="popMachineDesc">descripción de la máquina</div>
          </div>
        </div>
        <button class="ru-popup__close" onclick="closePopup()">&times;</button>
      </div>

      <!-- Stats (series x reps) -->
      <div class="ru-popup__stats">
        <div class="ru-popup__stat">
          <div class="ru-popup__stat-val" id="popSeries">4</div>
          <div class="ru-popup__stat-label">Series</div>
        </div>
        <div class="ru-popup__stat">
          <div class="ru-popup__stat-val" id="popReps">10</div>
          <div class="ru-popup__stat-label">Repeticiones</div>
        </div>
        <div class="ru-popup__stat">
          <div class="ru-popup__stat-val" id="popDescanso">90s</div>
          <div class="ru-popup__stat-label">Descanso</div>
        </div>
      </div>

      <!-- Foto de la máquina -->
      <div class="ru-popup__photo" id="popPhoto">
        <div class="ru-popup__photo--placeholder">
          <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
            <rect x="3" y="3" width="18" height="18" rx="2"/>
            <circle cx="8.5" cy="8.5" r="1.5"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21"/>
          </svg>
          <span>Foto</span>
        </div>
      </div>

      <!-- Técnica -->
      <div class="ru-popup__tecnica">
        <div class="ru-popup__tec-title">Técnica:</div>
        <div>
          <div class="ru-popup__tec-label">Ejercicio</div>
          <div class="ru-popup__tec-name" id="popTecName">—</div>
        </div>
        <div id="popTecExtra">
          <!-- Campos extra de técnica (músculo, notas) renderizados por JS -->
        </div>
      </div>

      <!-- Indicador de más ejercicios -->
      <div class="ru-popup__more" id="popMore" style="display:none;">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
        Desliza para ver más ejercicios
      </div>

    </div>
  </div>


  <!-- ══════════════════════════════════
       JAVASCRIPT
  ══════════════════════════════════ -->
  <script>
    const API_BASE = 'index.php?route=api&action=';

    async function apiRequest(action, options = {}) {
      const defaults = { headers: { 'Content-Type': 'application/json' } };
      const res = await fetch(API_BASE + action, { ...defaults, ...options });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.json();
    }

    /* ── State ── */
    let rutinaData   = null;  // { semanas: [...] }
    let semanas      = [];
    let currentSemana = 0;    // índice de semana activa
    let currentDay   = 0;     // índice de día activo dentro de la semana

    /* ══════════════════════════════
       BOOT
    ══════════════════════════════ */
    apiRequest('session')
      .then(data => {
        if (!data || !data.user) {
          window.location.href = 'index.php?route=login';
          return;
        }
        // Drawer user info
        const nombre = data.user.nombre || 'Usuario';
        const cedula = data.user.cedula || '';
        document.getElementById('drawerName').textContent   = nombre;
        document.getElementById('drawerCedula').textContent = cedula ? `CC ${cedula}` : '';
        document.getElementById('drawerAvatar').textContent =
          nombre.trim().charAt(0).toUpperCase();

        loadRutina();
      })
      .catch(() => {
        window.location.href = 'index.php?route=login&error=no_autorizado';
      });

    /* ══════════════════════════════
       LOAD RUTINA
    ══════════════════════════════ */
    async function loadRutina() {
      try {
        const data = await apiRequest('rutina_global');
        rutinaData = data;
        semanas    = data.semanas || [];

        document.getElementById('loadingState').style.display  = 'none';
        document.getElementById('rutinaContent').style.display = 'flex';
        document.getElementById('bottomBar').style.display     = 'flex';

        renderWeekTabs();
        renderDayCarousel();
      } catch (e) {
        document.getElementById('loadingState').innerHTML = `
          <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="opacity:.35">
            <circle cx="12" cy="12" r="10"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
          </svg>
          <span>No se pudo cargar la rutina.</span>`;
      }
    }

    /* ══════════════════════════════
       WEEK TABS
    ══════════════════════════════ */
    function renderWeekTabs() {
      const container = document.getElementById('weekTabs');
      container.innerHTML = semanas.map((s, i) => `
        <button
          class="ru-week-tab${i === currentSemana ? ' ru-week-tab--active' : ''}"
          onclick="selectSemana(${i})">
          ${escHtml(s.label || `Semana ${i + 1}`)}
        </button>`).join('');

      // Bottom bar dots
      updateBottomBar();
    }

    function selectSemana(idx) {
      currentSemana = idx;
      currentDay    = 0;
      renderWeekTabs();
      renderDayCarousel();
    }

    function updateBottomBar() {
      const total = Math.min(semanas.length, 4);
      const bar   = document.getElementById('bottomBar');
      bar.innerHTML = '';
      for (let i = 0; i < total; i++) {
        const d = document.createElement('div');
        d.className = `ru-bottom-bar__dot${i === currentSemana ? ' ru-bottom-bar__dot--active' : ''}`;
        d.onclick   = () => selectSemana(i);
        bar.appendChild(d);
      }
    }

    /* ══════════════════════════════
       DAY CAROUSEL
    ══════════════════════════════ */
    function renderDayCarousel() {
      const semana = semanas[currentSemana];
      const dias   = (semana && semana.dias) ? semana.dias : [];

      // Dots
      const dotsEl = document.getElementById('dayDots');
      dotsEl.innerHTML = dias.map((_, i) => `
        <div class="ru-day-dot${i === currentDay ? ' ru-day-dot--active' : ''}"
             onclick="goToDay(${i})"></div>`).join('');

      // Cards
      const track = document.getElementById('carouselTrack');
      if (!dias.length) {
        track.innerHTML = `
          <div class="ru-day-card" style="min-width:100%;">
            <div class="ru-day-empty">
              <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" />
              </svg>
              Sin días para esta semana.
            </div>
          </div>`;
      } else {
        track.innerHTML = dias.map((d, i) => buildDayCard(d, i)).join('');
      }

      updateCarouselPos();
      updateNavButtons(dias.length);
      updateNavLabel(dias.length);
    }

    function buildDayCard(dia, idx) {
      const ejercicios = dia.ejercicios || [];
      const exHtml = ejercicios.length
        ? ejercicios.map((e, ei) => `
            <div class="ru-ex-row" onclick="openPopup(${idx},${ei})">
              <div class="ru-ex-row__num">${ei + 1}</div>
              <div class="ru-ex-row__info">
                <div class="ru-ex-row__name">${escHtml(e.nombre || 'Ejercicio')}</div>
                <div class="ru-ex-row__detail">${escHtml(e.maquina || e.grupo_muscular || '')}</div>
              </div>
              <div class="ru-ex-row__arrow">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/>
                </svg>
              </div>
            </div>`).join('')
        : `<div class="ru-day-empty">
             <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
               <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
             </svg>
             Sin ejercicios para este día.
           </div>`;

      const grupoLabel = dia.grupo_muscular
        ? `<span class="ru-day-card__muscle">${escHtml(dia.grupo_muscular)}</span>` : '';

      return `
        <div class="ru-day-card">
          <div class="ru-day-card__head">
            <div class="ru-day-card__label">
              <div class="ru-day-card__num">${idx + 1}</div>
              <div class="ru-day-card__title">${escHtml(dia.titulo || `Día ${idx + 1}`)}</div>
            </div>
            ${grupoLabel}
          </div>
          <div class="ru-day-card__body">${exHtml}</div>
          ${ejercicios.length ? `
          <div class="ru-day-card__foot">
            <span class="ru-day-badge">${ejercicios.length} ejercicio${ejercicios.length !== 1 ? 's' : ''}</span>
          </div>` : ''}
        </div>`;
    }

    function updateCarouselPos() {
      document.getElementById('carouselTrack').style.transform =
        `translateX(-${currentDay * 100}%)`;
    }

    function updateNavButtons(total) {
      document.getElementById('prevBtn').disabled = currentDay <= 0;
      document.getElementById('nextBtn').disabled = currentDay >= total - 1;
    }

    function updateNavLabel(total) {
      document.getElementById('navLabel').textContent =
        total ? `Día ${currentDay + 1} de ${total}` : '—';
    }

    function goToDay(idx) {
      const semana = semanas[currentSemana];
      const total  = (semana && semana.dias) ? semana.dias.length : 0;
      if (idx < 0 || idx >= total) return;
      currentDay = idx;
      updateCarouselPos();
      updateNavButtons(total);
      updateNavLabel(total);
      // Update dots
      document.querySelectorAll('.ru-day-dot').forEach((d, i) => {
        d.classList.toggle('ru-day-dot--active', i === currentDay);
      });
    }

    function prevDay() { goToDay(currentDay - 1); }
    function nextDay() { goToDay(currentDay + 1); }

    /* Touch/swipe */
    let touchStartX = 0;
    document.getElementById('carouselTrack')?.addEventListener('touchstart', e => {
      touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });
    document.getElementById('carouselTrack')?.addEventListener('touchend', e => {
      const diff = touchStartX - e.changedTouches[0].screenX;
      if (Math.abs(diff) > 50) diff > 0 ? nextDay() : prevDay();
    }, { passive: true });

    /* ══════════════════════════════
       POPUP
    ══════════════════════════════ */
    function openPopup(dayIdx, exIdx) {
      const semana     = semanas[currentSemana];
      const dia        = semana && semana.dias ? semana.dias[dayIdx] : null;
      const ejercicio  = dia && dia.ejercicios ? dia.ejercicios[exIdx] : null;
      if (!ejercicio) return;

      const total = dia.ejercicios.length;

      document.getElementById('popNum').textContent          = exIdx + 1;
      document.getElementById('popName').textContent         = ejercicio.nombre || 'Ejercicio';
      document.getElementById('popMachineDesc').textContent  = ejercicio.maquina || ejercicio.descripcion_maquina || '';
      document.getElementById('popSeries').textContent       = ejercicio.series   || '—';
      document.getElementById('popReps').textContent         = ejercicio.reps     || '—';
      document.getElementById('popDescanso').textContent     = ejercicio.descanso || '—';
      document.getElementById('popTecName').textContent      = ejercicio.tecnica  || ejercicio.nombre || '—';

      // Extra tecnica fields
      const extras = [];
      if (ejercicio.grupo_muscular) extras.push({ label: 'Músculo', val: ejercicio.grupo_muscular });
      if (ejercicio.notas)          extras.push({ label: 'Notas',   val: ejercicio.notas });
      document.getElementById('popTecExtra').innerHTML = extras.map(f => `
        <div>
          <div class="ru-popup__tec-label">${escHtml(f.label)}</div>
          <div class="ru-popup__tec-text">${escHtml(f.val)}</div>
        </div>`).join('');

      // Photo
      const photoEl = document.getElementById('popPhoto');
      if (ejercicio.foto_maquina || ejercicio.foto) {
        photoEl.innerHTML = `<img src="${escHtml(ejercicio.foto_maquina || ejercicio.foto)}" alt="${escHtml(ejercicio.nombre)}">`;
      } else {
        photoEl.innerHTML = `
          <div class="ru-popup__photo--placeholder">
            <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
              <rect x="3" y="3" width="18" height="18" rx="2"/>
              <circle cx="8.5" cy="8.5" r="1.5"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21"/>
            </svg>
            <span>Foto</span>
          </div>`;
      }

      // More indicator
      const moreEl = document.getElementById('popMore');
      moreEl.style.display = (total > 1) ? 'flex' : 'none';

      document.getElementById('popupOverlay').classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function closePopup() {
      document.getElementById('popupOverlay').classList.remove('open');
      document.body.style.overflow = '';
    }

    function handlePopupOverlayClick(e) {
      if (e.target === document.getElementById('popupOverlay')) closePopup();
    }

    /* ══════════════════════════════
       DRAWER
    ══════════════════════════════ */
    function openDrawer() {
      document.getElementById('drawer').classList.add('open');
      document.getElementById('drawerOverlay').classList.add('open');
      document.body.style.overflow = 'hidden';
    }
    function closeDrawer() {
      document.getElementById('drawer').classList.remove('open');
      document.getElementById('drawerOverlay').classList.remove('open');
      document.body.style.overflow = '';
    }

    /* ESC closes everything */
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') { closePopup(); closeDrawer(); }
    });

    /* ── Escape HTML ── */
    function escHtml(str) {
      return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    /* ── Expose globals ── */
    window.openDrawer   = openDrawer;
    window.closeDrawer  = closeDrawer;
    window.selectSemana = selectSemana;
    window.prevDay      = prevDay;
    window.nextDay      = nextDay;
    window.goToDay      = goToDay;
    window.openPopup    = openPopup;
    window.closePopup   = closePopup;
    window.handlePopupOverlayClick = handlePopupOverlayClick;
  </script>

</body>
</html>
