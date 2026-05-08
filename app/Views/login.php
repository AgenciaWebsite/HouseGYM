<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ingreso - HouseGYM</title>
  <link rel="stylesheet" href="assets/login.css">
  <style>
    /* ── Loading Overlay ───────────────────────────────────────────── */
    #loadingOverlay {
      position: fixed;
      inset: 0;
      z-index: 9999;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 28px;

      /* starts hidden */
      opacity: 0;
      pointer-events: none;
      visibility: hidden;

      transition: opacity 0.3s ease, visibility 0.3s ease;

      /* frosted glass dark background */
      background: rgba(8, 8, 14, 0.85);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
    }

    #loadingOverlay.active {
      opacity: 1;
      pointer-events: all;
      visibility: visible;
    }

    /* ── Barbell spinner ───────────────────────────────────────────── */
    .loader-barbell {
      position: relative;
      width: 120px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: barbell-bounce 0.7s ease-in-out infinite alternate;
    }

    @keyframes barbell-bounce {
      from {
        transform: translateY(0px);
      }

      to {
        transform: translateY(-10px);
      }
    }

    /* bar */
    .barbell-bar {
      position: absolute;
      width: 100%;
      height: 6px;
      border-radius: 3px;
      background: linear-gradient(90deg, #4a2020 0%, #c07070 40%, #f4a0a0 50%, #c07070 60%, #4a2020 100%);
      box-shadow: 0 0 12px rgba(220, 80, 80, 0.3);
    }

    /* weights – shared */
    .barbell-weight {
      position: absolute;
      border-radius: 4px;
      background: linear-gradient(160deg, #4a2020 0%, #2a0f0f 100%);
      border: 1.5px solid rgba(220, 100, 100, 0.25);
      box-shadow:
        inset 0 1px 0 rgba(255, 160, 160, 0.1),
        0 4px 12px rgba(0, 0, 0, 0.5);
    }

    /* outer plates */
    .barbell-weight--outer {
      width: 18px;
      height: 36px;
    }

    .barbell-weight--outer.left {
      left: 0;
    }

    .barbell-weight--outer.right {
      right: 0;
    }

    /* inner plates */
    .barbell-weight--inner {
      width: 13px;
      height: 28px;
    }

    .barbell-weight--inner.left {
      left: 16px;
    }

    .barbell-weight--inner.right {
      right: 16px;
    }

    /* accent line on each plate */
    .barbell-weight::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 3px;
      height: 60%;
      border-radius: 2px;
      background: rgba(255, 160, 160, 0.1);
    }

    /* glow dot in center of bar */
    .barbell-center {
      position: absolute;
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: #f4a0a0;
      box-shadow:
        0 0 8px 4px rgba(220, 80, 80, 0.45),
        0 0 20px 8px rgba(180, 40, 40, 0.25);
      animation: center-pulse 1.4s ease-in-out infinite;
    }

    @keyframes center-pulse {

      0%,
      100% {
        opacity: 1;
        transform: scale(1);
      }

      50% {
        opacity: 0.5;
        transform: scale(0.75);
      }
    }

    /* shadow below barbell (depth) */
    .barbell-shadow {
      position: absolute;
      bottom: -16px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 8px;
      border-radius: 50%;
      background: rgba(0, 0, 0, 0.5);
      filter: blur(6px);
      animation: shadow-scale 0.7s ease-in-out infinite alternate;
    }

    @keyframes shadow-scale {
      from {
        width: 80px;
        opacity: 0.6;
      }

      to {
        width: 50px;
        opacity: 0.2;
      }
    }

    /* ── Text & dots ───────────────────────────────────────────────── */
    .loader-text {
      font-family: system-ui, sans-serif;
      font-size: 0.85rem;
      letter-spacing: 0.18em;
      text-transform: uppercase;
      color: rgba(240, 160, 160, 0.7);
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .loader-dots span {
      display: inline-block;
      animation: dot-blink 1.2s ease-in-out infinite;
      opacity: 0;
    }

    .loader-dots span:nth-child(1) {
      animation-delay: 0s;
    }

    .loader-dots span:nth-child(2) {
      animation-delay: 0.2s;
    }

    .loader-dots span:nth-child(3) {
      animation-delay: 0.4s;
    }

    @keyframes dot-blink {

      0%,
      80%,
      100% {
        opacity: 0;
      }

      40% {
        opacity: 1;
      }
    }

    /* ── Progress bar ──────────────────────────────────────────────── */
    .loader-progress-track {
      width: 200px;
      height: 2px;
      background: rgba(220, 80, 80, 0.12);
      border-radius: 2px;
      overflow: hidden;
    }

    .loader-progress-fill {
      height: 100%;
      width: 0%;
      border-radius: 2px;
      background: linear-gradient(90deg, rgba(180, 60, 60, 0.4), rgba(240, 120, 120, 0.95));
      box-shadow: 0 0 8px rgba(220, 80, 80, 0.5);
      transition: width 0.15s ease;
    }

    /* ── Button loading state ──────────────────────────────────────── */
    .btn-submit.loading {
      opacity: 0.6;
      pointer-events: none;
    }
  </style>
</head>

<body>

  <!-- Background -->
  <div class="bg-glow bg-glow--top-left"></div>
  <div class="bg-glow bg-glow--bottom-right"></div>
  <div class="bg-image"></div>

  <!-- ── Loading overlay ───────────────────────────────────────────── -->
  <div id="loadingOverlay" role="status" aria-live="polite" aria-label="Verificando credenciales">
    <div class="loader-barbell">
      <div class="barbell-bar"></div>
      <div class="barbell-weight barbell-weight--outer left"></div>
      <div class="barbell-weight barbell-weight--inner left"></div>
      <div class="barbell-weight barbell-weight--inner right"></div>
      <div class="barbell-weight barbell-weight--outer right"></div>
      <div class="barbell-center"></div>
      <div class="barbell-shadow"></div>
    </div>

    <p class="loader-text">
      Verificando
      <span class="loader-dots">
        <span>.</span><span>.</span><span>.</span>
      </span>
    </p>

    <div class="loader-progress-track">
      <div class="loader-progress-fill" id="progressFill"></div>
    </div>
  </div>

  <!-- Card -->
  <div class="login-card">

    <!-- Animated top border -->
    <div class="login-card__border"></div>

    <!-- Header -->
    <div class="login-card__header">
      <h2 class="login-card__title">
        Ingreso a
        <span>HouseGYM</span>
      </h2>
      <p class="login-card__subtitle">Ingresa con tu cuenta de usuario</p>
      <div class="login-error" id="loginError"></div>
    </div>

    <!-- Form -->
    <form action="index.php?route=login" method="POST" class="login-form" id="loginForm">

      <!-- Cedula -->
      <div class="field">
        <label class="field__label">Cedula</label>
        <div class="field__input-wrap">
          <div class="field__icon">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 9h16 M4 15h16 M10 3L8 21 M16 3l-2 18" />
            </svg>
          </div>
          <input type="text" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/\D/g, '')"
            name="cedula" placeholder="Documento" class="field__input" required>
        </div>
      </div>

      <!-- Contraseña -->
      <div class="field">
        <label class="field__label">Contraseña</label>
        <div class="field__input-wrap">
          <div class="field__icon">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
          </div>
          <input type="password" name="password" placeholder="*******" class="field__input" required>
        </div>
      </div>

      <!-- Submit -->
      <button type="submit" class="btn-submit" id="submitBtn">
        Ingresar
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
        </svg>
        <span class="btn-submit__ripple">
          <span class="btn-submit__ripple-circle"></span>
        </span>
      </button>

    </form>
  </div>

  <script>
    /* ── Error messages ─────────────────────────────────────────── */
    const params = new URLSearchParams(window.location.search);
    const error = params.get('error');
    const messages = {
      falten_datos: 'Ingresa usuario y contraseña.',
      credenciales_invalidas: 'Las credenciales no coinciden con un usuario activo.',
      conexion: 'No se pudo conectar con la base de datos.',
      no_autorizado: 'Inicia sesión como administrador para entrar al panel.',
    };

    if (error) {
      const box = document.getElementById('loginError');
      box.textContent = messages[error] || 'No se pudo iniciar sesión.';
      box.classList.add('login-error--visible');
    }

    /* ── Loading screen ─────────────────────────────────────────── */
    const form = document.getElementById('loginForm');
    const submitBtn = document.getElementById('submitBtn');
    const overlay = document.getElementById('loadingOverlay');
    const progressBar = document.getElementById('progressFill');

    function animateProgress() {
      /* Simulates progress: fast to 70 %, then slows until form submits */
      const steps = [
        { target: 25, delay: 80 },
        { target: 55, delay: 200 },
        { target: 70, delay: 400 },
        { target: 82, delay: 700 },
        { target: 90, delay: 1000 },
        { target: 95, delay: 1400 },
      ];

      steps.forEach(({ target, delay }) => {
        setTimeout(() => {
          progressBar.style.width = target + '%';
        }, delay);
      });
    }

    form.addEventListener('submit', function (e) {
      const cedula = form.querySelector('[name="cedula"]').value.trim();
      const password = form.querySelector('[name="password"]').value.trim();

      /* Only show loader when both fields are filled (HTML5 validation
         will catch empty fields, but we add an extra guard here) */
      if (!cedula || !password) return;

      /* Show overlay */
      overlay.classList.add('active');
      submitBtn.classList.add('loading');
      animateProgress();

      /* Let the form submit naturally — the overlay stays visible
         until the browser navigates away to the next page. */
    });
  </script>

</body>

</html>