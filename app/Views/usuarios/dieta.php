<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi Dieta - HouseGYM</title>
  <link rel="stylesheet" href="assets/admin/dashboard.css">
  <link rel="stylesheet" href="assets/usuarios/dieta.css">
</head>

<body>

  <div class="bg-glow bg-glow--top-right"></div>
  <div class="bg-glow bg-glow--bottom-left"></div>

  <?php $current_page = 'dieta'; include 'sidebar.php'; ?>

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
        <h1>Mi <span>Dieta</span></h1>
        <p class="page-subtitle">Plan de alimentación asignado por tu entrenador.</p>
      </div>

      <!-- Diet card (expandable) -->
      <div class="diet-card" id="dietCard" style="display:none;">

        <div class="diet-card__trigger" id="dietTrigger">
          <div class="diet-card__icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
          </div>
          <div class="diet-card__meta">
            <div class="diet-card__label">Plan de alimentación</div>
            <div class="diet-card__name" id="dietName">—</div>
            <div class="diet-card__desc" id="dietDesc">Haz clic para ver tu plan completo</div>
          </div>
          <div class="diet-card__right">
            <span class="diet-badge">Activa</span>
            <div class="diet-card__chevron">
              <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Expandable body -->
        <div class="diet-card__body">
          <div class="diet-card__body-inner">
            <div class="diet-card__divider"></div>
            <div class="diet-viewer" id="dietViewer">
              <!-- Filled by JS -->
            </div>
          </div>
        </div>

      </div><!-- /diet-card -->

      <!-- No diet state -->
      <div class="diet-empty-state" id="dietEmptyState" style="display:none;">
        <div class="empty-icon">
          <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h3>Sin dieta asignada</h3>
        <p>Aún no tienes un plan de alimentación. Acércate a recepción para que tu entrenador te asigne uno.</p>
      </div>

      <!-- Loading state -->
      <div class="diet-loading" id="dietLoading">
        <div class="diet-loading__spinner"></div>
        <p>Cargando tu plan...</p>
      </div>

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
       TOGGLE CARD
    ══════════════════════════════════════ */
    let isOpen = false;
    document.getElementById('dietTrigger').addEventListener('click', () => {
      isOpen = !isOpen;
      document.getElementById('dietCard').classList.toggle('diet-card--open', isOpen);
    });

    /* ══════════════════════════════════════
       RENDER VIEWER
       Espera del API:
         data.diet.archivo_url  → ruta al archivo
         data.diet.archivo_tipo → 'imagen' | 'pdf' | null
    ══════════════════════════════════════ */
    function renderViewer(diet) {
      const viewer = document.getElementById('dietViewer');

      if (!diet.archivo_url || !diet.archivo_tipo) {
        viewer.innerHTML = `
          <div class="viewer-empty">
            <div class="viewer-empty__icon">
              <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
            </div>
            <p>Tu entrenador aún no ha subido el archivo de tu plan. Pronto estará disponible aquí.</p>
          </div>`;
        return;
      }

      if (diet.archivo_tipo === 'imagen') {
        viewer.innerHTML = `
          <div class="viewer-img-wrap">
            <img src="${diet.archivo_url}" alt="Plan de dieta">
          </div>
          <div class="viewer-actions">
            <a class="btn-download" href="${diet.archivo_url}" download>
              <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
              </svg>
              Descargar imagen
            </a>
          </div>`;
      }

      if (diet.archivo_tipo === 'pdf') {
        viewer.innerHTML = `
          <div class="viewer-pdf-wrap">
            <iframe src="${diet.archivo_url}" title="Plan de dieta"></iframe>
          </div>
          <div class="viewer-actions">
            <a class="btn-download" href="${diet.archivo_url}" download>
              <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
              </svg>
              Descargar PDF
            </a>
          </div>`;
      }
    }

    /* ══════════════════════════════════════
       INIT
    ══════════════════════════════════════ */
    async function init() {
      // Topbar user
      const dataProfile = await fetchApi('profile');
      if (dataProfile && dataProfile.profile) {
        const firstName = dataProfile.profile.nombre.split(' ')[0];
        document.getElementById('topbarUserName').textContent = firstName;
        setInitials(dataProfile.profile.nombre);
      }

      // Diet data
      const dataDiet = await fetchApi('diet');

      document.getElementById('dietLoading').style.display = 'none';

      if (!dataDiet || !dataDiet.diet || !dataDiet.diet.id_dieta) {
        document.getElementById('dietEmptyState').style.display = 'flex';
        return;
      }

      const diet = dataDiet.diet;
      document.getElementById('dietName').textContent = diet.nombre || 'Personalizada';
      if (diet.descripcion) {
        document.getElementById('dietDesc').textContent = diet.descripcion;
      }
      renderViewer(diet);
      document.getElementById('dietCard').style.display = 'block';
    }

    init();
  </script>
</body>

</html>
