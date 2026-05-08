<?php
declare(strict_types=1);

session_start();

// Autoloader básico para el proyecto
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\UsuarioController;

$route = $_GET['route'] ?? 'login';
$method = $_SERVER['REQUEST_METHOD'];

switch ($route) {
    case 'login':
        $authController = new AuthController();
        if ($method === 'POST') {
            $authController->login();
        } else {
            $authController->showLogin();
        }
        break;

    case 'logout':
        $authController = new AuthController();
        $authController->logout();
        break;

    case 'admin':
        // Mostrar la vista de admin
        if (($_SESSION['rol'] ?? '') !== 'admin' || empty($_SESSION['admin_id'])) {
            header('Location: index.php?route=login&error=no_autorizado');
            exit;
        }
        require_once __DIR__ . '/app/Views/admin.php';
        break;

    case 'admin_api':
        // Manejar las peticiones de la API para la vista admin
        $adminController = new AdminController();
        $resource = (string) ($_GET['resource'] ?? 'dashboard');
        $adminController->handleRequest($method, $resource);
        break;

    case 'usuario_api':
        // Manejar las peticiones de la API para la vista de usuario
        $usuarioController = new UsuarioController();
        $resource = (string) ($_GET['resource'] ?? 'profile');
        $usuarioController->handleRequest($method, $resource);
        break;

    case 'usuarios':
        // Mostrar la vista de usuarios (para el usuario cliente)
        if (empty($_SESSION['rol'])) {
            header('Location: index.php?route=login&error=no_autorizado');
            exit;
        }
        require_once __DIR__ . '/app/Views/usuarios.php';
        break;

    case 'admin_usuarios':
        // Mostrar la vista de gestión de usuarios para el admin
        if (($_SESSION['rol'] ?? '') !== 'admin' || empty($_SESSION['admin_id'])) {
            header('Location: index.php?route=login&error=no_autorizado');
            exit;
        }
        require_once __DIR__ . '/app/Views/admin_usuarios.php';
        break;

    case 'admin_rutina_personalizada':
        if (($_SESSION['rol'] ?? '') !== 'admin' || empty($_SESSION['admin_id'])) {
            header('Location: index.php?route=login&error=no_autorizado');
            exit;
        }
        require_once __DIR__ . '/app/Views/admin_rutina_personalizada.php';
        break;

    case 'admin_rutina_global':
        if (($_SESSION['rol'] ?? '') !== 'admin' || empty($_SESSION['admin_id'])) {
            header('Location: index.php?route=login&error=no_autorizado');
            exit;
        }
        require_once __DIR__ . '/app/Views/admin_rutina_global.php';
        break;

    case 'admin_maquinas':
        if (($_SESSION['rol'] ?? '') !== 'admin' || empty($_SESSION['admin_id'])) {
            header('Location: index.php?route=login&error=no_autorizado');
            exit;
        }
        require_once __DIR__ . '/app/Views/admin_maquinas.php';
        break;

    default:
        // Ruta no encontrada, redirigir al login
        header('Location: index.php?route=login');
        exit;
}
