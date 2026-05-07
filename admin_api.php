<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/database/conexion.php';

header('Content-Type: application/json; charset=utf-8');

function json_response(array $payload, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function require_admin(): void
{
    if (($_SESSION['rol'] ?? '') !== 'admin' || empty($_SESSION['admin_id'])) {
        json_response(['ok' => false, 'error' => 'no_autorizado'], 401);
    }
}

function scalar_count(PDO $pdo, string $sql): int
{
    return (int) $pdo->query($sql)->fetchColumn();
}

function password_for_storage(string $password): string
{
    return password_hash($password, PASSWORD_DEFAULT);
}

$method = $_SERVER['REQUEST_METHOD'];
$resource = (string) ($_GET['resource'] ?? 'dashboard');

try {
    if ($resource === 'session') {
        require_admin();
        json_response([
            'ok' => true,
            'admin' => [
                'id' => (int) $_SESSION['admin_id'],
                'usuario' => (string) $_SESSION['admin_usuario'],
            ],
        ]);
    }

    require_admin();
    $pdo = db();

    if ($method === 'GET' && $resource === 'dashboard') {
        $stats = [
            'usuarios' => scalar_count($pdo, 'SELECT COUNT(*) FROM usuarios'),
            'rutinas_personalizadas' => scalar_count($pdo, 'SELECT COUNT(*) FROM rutina_personalizada'),
            'maquinas' => scalar_count($pdo, 'SELECT COUNT(*) FROM maquinas'),
            'dietas' => scalar_count($pdo, 'SELECT COUNT(*) FROM dietas'),
            'ejercicios' => scalar_count($pdo, 'SELECT COUNT(*) FROM ejercicios'),
            'rutinas_globales' => scalar_count($pdo, 'SELECT COUNT(*) FROM rutina_global'),
        ];

        $recentStmt = $pdo->query(
            'SELECT id_usuario, nombre, cedula, activo, plan_personalizado
             FROM usuarios
             ORDER BY id_usuario DESC
             LIMIT 5'
        );

        $chartStmt = $pdo->query(
            'SELECT id_usuario, cedula
             FROM usuarios
             ORDER BY id_usuario ASC'
        );
        $allUsers = $chartStmt->fetchAll();
        $totalUsers = count($allUsers);
        $chart = [];
        $monthLabels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $currentMonth = (int) date('n') - 1;

        for ($i = 6; $i >= 0; $i--) {
            $monthIndex = ($currentMonth - $i + 12) % 12;
            $chart[] = [
                'month' => $monthLabels[$monthIndex],
                'val' => $totalUsers === 0 ? 0 : (int) ceil($totalUsers * (7 - $i) / 7),
            ];
        }

        json_response([
            'ok' => true,
            'stats' => $stats,
            'recent_users' => $recentStmt->fetchAll(),
            'chart' => $chart,
        ]);
    }

    if ($method === 'GET' && $resource === 'search') {
        $q = trim((string) ($_GET['q'] ?? ''));
        if (strlen($q) < 2) {
            json_response(['ok' => true, 'results' => []]);
        }

        $like = '%' . $q . '%';
        $results = [];

        $stmt = $pdo->prepare(
            "SELECT 'usuario' AS tipo, id_usuario AS id, nombre AS titulo,
                    CONCAT('CC ', cedula, IF(activo = 1, ' · Activo', ' · Inactivo')) AS detalle
             FROM usuarios
             WHERE nombre LIKE ? OR CAST(cedula AS CHAR) LIKE ?
             LIMIT 5"
        );
        $stmt->execute([$like, $like]);
        $results = array_merge($results, $stmt->fetchAll());

        $stmt = $pdo->prepare(
            "SELECT 'maquina' AS tipo, id_maquina AS id, nombre AS titulo,
                    CONCAT('Piso ', piso, ' · Maquina') AS detalle
             FROM maquinas
             WHERE nombre LIKE ?
             LIMIT 5"
        );
        $stmt->execute([$like]);
        $results = array_merge($results, $stmt->fetchAll());

        $stmt = $pdo->prepare(
            "SELECT 'ejercicio' AS tipo, id_ejercicio AS id, nombre AS titulo,
                    'Ejercicio' AS detalle
             FROM ejercicios
             WHERE nombre LIKE ?
             LIMIT 5"
        );
        $stmt->execute([$like]);
        $results = array_merge($results, $stmt->fetchAll());

        json_response(['ok' => true, 'results' => array_slice($results, 0, 10)]);
    }

    if ($method === 'POST' && $resource === 'users') {
        $data = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($data)) {
            $data = $_POST;
        }

        $nombre = trim((string) ($data['nombre'] ?? ''));
        $cedula = trim((string) ($data['cedula'] ?? ''));
        $password = (string) ($data['contrasena'] ?? $data['password'] ?? '');
        $plan = !empty($data['plan_personalizado']) ? 1 : 0;
        $dieta = !empty($data['dieta']);

        if ($nombre === '' || $cedula === '' || $password === '') {
            json_response(['ok' => false, 'error' => 'faltan_datos'], 422);
        }

        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            'INSERT INTO usuarios (nombre, cedula, contrasena, activo, plan_personalizado)
             VALUES (?, ?, ?, 1, ?)'
        );
        $stmt->execute([$nombre, $cedula, password_for_storage($password), $plan]);
        $userId = (int) $pdo->lastInsertId();

        if ($plan || $dieta) {
            $dietId = null;
            if ($dieta) {
                $dietId = $pdo->query('SELECT id_dieta FROM dietas ORDER BY id_dieta ASC LIMIT 1')->fetchColumn();
                $dietId = $dietId ? (int) $dietId : null;
            }

            $stmt = $pdo->prepare(
                'INSERT INTO rutina_personalizada (id_usuario, nombre, activa, id_dieta)
                 VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([$userId, 'Rutina personalizada', $plan, $dietId]);
        }

        $pdo->commit();
        json_response(['ok' => true, 'id_usuario' => $userId], 201);
    }

    if ($method === 'DELETE' && $resource === 'users') {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            json_response(['ok' => false, 'error' => 'id_invalido'], 422);
        }

        $stmt = $pdo->prepare('DELETE FROM usuarios WHERE id_usuario = ?');
        $stmt->execute([$id]);

        json_response(['ok' => true]);
    }

    json_response(['ok' => false, 'error' => 'recurso_no_soportado'], 404);
} catch (PDOException $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $status = $e->getCode() === '23000' ? 409 : 500;
    json_response(['ok' => false, 'error' => 'base_de_datos'], $status);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => 'servidor'], 500);
}
