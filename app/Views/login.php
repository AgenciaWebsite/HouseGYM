<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ingreso - HouseGYM</title>
  <link rel="stylesheet" href="assets/login.css">
</head>

<body>

  <!-- Background -->
  <div class="bg-glow bg-glow--top-left"></div>
  <div class="bg-glow bg-glow--bottom-right"></div>
  <div class="bg-image"></div>

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
    <form action="index.php?route=login" method="POST" class="login-form">

      <!-- Cedula -->
      <div class="field">
        <label class="field__label">Cedula</label>
        <div class="field__input-wrap">
          <div class="field__icon">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 9h16 M4 15h16 M10 3L8 21 M16 3l-2 18" />
            </svg>
          </div>
          <input
            type="text"
            inputmode="numeric"
            pattern="[0-9]*"
            oninput="this.value = this.value.replace(/\D/g, '')"
            name="cedula"
            placeholder="Documento"
            class="field__input"
            required>
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
          <input
            type="password"
            name="password"
            placeholder="*******"
            class="field__input"
            required>
        </div>
      </div>

      <!-- Submit -->
      <button type="submit" class="btn-submit">
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
    const params = new URLSearchParams(window.location.search);
    const error  = params.get('error');
    const messages = {
      faltan_datos:          'Ingresa usuario y contrasena.',
      credenciales_invalidas: 'Las credenciales no coinciden con un usuario activo.',
      conexion:              'No se pudo conectar con la base de datos.',
      no_autorizado:         'Inicia sesion como administrador para entrar al panel.',
    };

    if (error) {
      const box = document.getElementById('loginError');
      box.textContent = messages[error] || 'No se pudo iniciar sesion.';
      box.classList.add('login-error--visible');
    }
  </script>

</body>

</html>
