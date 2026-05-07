<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use PDO;
use Throwable;

/**
 * Clase AuthController
 * 
 * Gestiona el inicio de sesión, validación de credenciales y cierre de sesión.
 */
class AuthController
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Muestra la vista de inicio de sesión.
     */
    public function showLogin(): void
    {
        // Se asume que este método es llamado desde index.php
        require_once __DIR__ . '/../Views/login.php';
    }

    /**
     * Procesa la solicitud POST para iniciar sesión.
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=login');
            exit;
        }

        $usuario = trim((string) ($_POST['cedula'] ?? $_POST['usuario'] ?? ''));
        $password = (string) ($_POST['password'] ?? $_POST['contrasena'] ?? '');

        if ($usuario === '' || $password === '') {
            $this->redirectLogin('faltan_datos');
        }

        try {
            // Revisar tabla admins
            $stmt = $this->pdo->prepare('SELECT id_admin, usuario, contrasena FROM admins WHERE usuario = ? LIMIT 1');
            $stmt->execute([$usuario]);
            $admin = $stmt->fetch();

            if ($admin && $this->passwordMatches($password, (string) $admin['contrasena'])) {
                session_regenerate_id(true);
                $_SESSION['admin_id'] = (int) $admin['id_admin'];
                $_SESSION['admin_usuario'] = (string) $admin['usuario'];
                $_SESSION['rol'] = 'admin';
                header('Location: index.php?route=admin');
                exit;
            }

            // Revisar tabla usuarios
            $stmt = $this->pdo->prepare('SELECT id_usuario, cedula, contrasena, activo FROM usuarios WHERE cedula = ? LIMIT 1');
            $stmt->execute([$usuario]);
            $user = $stmt->fetch();

            if ($user && (int) $user['activo'] === 1 && $this->passwordMatches($password, (string) $user['contrasena'])) {
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = (int) $user['id_usuario'];
                $_SESSION['cedula'] = (string) $user['cedula'];
                $_SESSION['rol'] = 'usuario';
                header('Location: index.php?route=usuarios');
                exit;
            }

            $this->redirectLogin('credenciales_invalidas');
        } catch (Throwable $e) {
            $this->redirectLogin('conexion');
        }
    }

    /**
     * Destruye la sesión actual y redirige a login.
     */
    public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: index.php?route=login');
        exit;
    }

    /**
     * Compara contraseña plana con hash o texto plano.
     */
    private function passwordMatches(string $plain, string $stored): bool
    {
        if ($stored === '') {
            return false;
        }
        return password_verify($plain, $stored) || hash_equals($stored, $plain);
    }

    /**
     * Redirige al formulario de login con un error.
     */
    private function redirectLogin(string $error): void
    {
        header('Location: index.php?route=login&error=' . rawurlencode($error));
        exit;
    }
}
