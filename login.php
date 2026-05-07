<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/database/conexion.php';

function redirect_login(string $error): void
{
    header('Location: login.html?error=' . rawurlencode($error));
    exit;
}

function password_matches(string $plain, string $stored): bool
{
    if ($stored === '') {
        return false;
    }

    return password_verify($plain, $stored) || hash_equals($stored, $plain);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.html');
    exit;
}

$usuario = trim((string) ($_POST['cedula'] ?? $_POST['usuario'] ?? ''));
$password = (string) ($_POST['password'] ?? $_POST['contrasena'] ?? '');

if ($usuario === '' || $password === '') {
    redirect_login('faltan_datos');
}

try {
    $pdo = db();

    $stmt = $pdo->prepare('SELECT id_admin, usuario, contrasena FROM admins WHERE usuario = ? LIMIT 1');
    $stmt->execute([$usuario]);
    $admin = $stmt->fetch();

    if ($admin && password_matches($password, (string) $admin['contrasena'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int) $admin['id_admin'];
        $_SESSION['admin_usuario'] = (string) $admin['usuario'];
        $_SESSION['rol'] = 'admin';
        header('Location: admin.html');
        exit;
    }

    $stmt = $pdo->prepare('SELECT id_usuario, cedula, contrasena, activo FROM usuarios WHERE cedula = ? LIMIT 1');
    $stmt->execute([$usuario]);
    $user = $stmt->fetch();

    if ($user && (int) $user['activo'] === 1 && password_matches($password, (string) $user['contrasena'])) {
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = (int) $user['id_usuario'];
        $_SESSION['cedula'] = (string) $user['cedula'];
        $_SESSION['rol'] = 'usuario';
        header('Location: referencias/usuarios.html');
        exit;
    }

    redirect_login('credenciales_invalidas');
} catch (Throwable $e) {
    redirect_login('conexion');
}
