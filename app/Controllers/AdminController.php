<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\AdminModel;

/**
 * Clase AdminController
 * 
 * Gestiona las rutas y lógica de negocio para la API del panel de administración.
 */
class AdminController
{
    private AdminModel $model;

    public function __construct()
    {
        $this->model = new AdminModel();
    }

    /**
     * Valida que exista una sesión de administrador activa.
     */
    private function requireAdmin(): void
    {
        if (($_SESSION['rol'] ?? '') !== 'admin' || empty($_SESSION['admin_id'])) {
            $this->jsonResponse(['ok' => false, 'error' => 'no_autorizado'], 401);
        }
    }

    /**
     * Envía una respuesta JSON al cliente y termina la ejecución.
     *
     * @param array $payload Datos a enviar.
     * @param int $status Código HTTP.
     */
    private function jsonResponse(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Enrutador principal de las peticiones para el Admin.
     *
     * @param string $method Método HTTP (GET, POST, DELETE, etc).
     * @param string $resource Recurso solicitado.
     */
    public function handleRequest(string $method, string $resource): void
    {
        try {
            if ($resource === 'session') {
                $this->requireAdmin();
                $this->jsonResponse([
                    'ok' => true,
                    'admin' => [
                        'id' => (int) $_SESSION['admin_id'],
                        'usuario' => (string) $_SESSION['admin_usuario'],
                    ],
                ]);
            }

            $this->requireAdmin();

            if ($method === 'GET' && $resource === 'dashboard') {
                $this->getDashboard();
            }

            if ($method === 'GET' && $resource === 'search') {
                $this->search();
            }

            if ($method === 'GET' && $resource === 'users') {
                $this->getUsers();
            }

            if ($method === 'POST' && $resource === 'users') {
                $this->addUser();
            }

            if ($method === 'PUT' && $resource === 'users') {
                $this->updateUser();
            }

            if ($method === 'DELETE' && $resource === 'users') {
                $this->deleteUser();
            }

            if ($method === 'GET' && $resource === 'ejercicios') {
                $this->getEjercicios();
            }

            if ($method === 'GET' && $resource === 'rutina_personalizada') {
                $this->getRutinaPersonalizada();
            }

            if ($method === 'POST' && $resource === 'rutina_personalizada') {
                $this->saveRutinaPersonalizada();
            }

            if ($method === 'GET' && $resource === 'machines') {
                $this->getMachines();
            }

            if ($method === 'POST' && $resource === 'machines') {
                $this->addMachine();
            }

            if ($method === 'PUT' && $resource === 'machines') {
                $this->updateMachine();
            }

            if ($method === 'DELETE' && $resource === 'machines') {
                $this->deleteMachine();
            }

            $this->jsonResponse(['ok' => false, 'error' => 'recurso_no_soportado'], 404);

        } catch (\PDOException $e) {
            $status = $e->getCode() === '23000' ? 409 : 500;
            $this->jsonResponse(['ok' => false, 'error' => 'base_de_datos'], $status);
        } catch (\Throwable $e) {
            $this->jsonResponse(['ok' => false, 'error' => 'servidor', 'msg' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtiene y formatea los datos del dashboard.
     */
    private function getDashboard(): void
    {
        $stats = $this->model->getDashboardStats();
        $recentUsers = $this->model->getRecentUsers(5);
        $allUsers = $this->model->getAllUsersForChart();
        
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

        $this->jsonResponse([
            'ok' => true,
            'stats' => $stats,
            'recent_users' => $recentUsers,
            'chart' => $chart,
        ]);
    }

    /**
     * Ejecuta una búsqueda global usando un parámetro GET 'q'.
     */
    private function search(): void
    {
        $q = trim((string) ($_GET['q'] ?? ''));
        if (strlen($q) < 2) {
            $this->jsonResponse(['ok' => true, 'results' => []]);
        }

        $results = $this->model->searchGlobal($q);
        $this->jsonResponse(['ok' => true, 'results' => $results]);
    }

    /**
     * Procesa la solicitud para agregar un usuario.
     */
    private function addUser(): void
    {
        $data = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($data)) {
            $data = $_POST;
        }

        $nombre = trim((string) ($data['nombre'] ?? ''));
        $cedula = trim((string) ($data['cedula'] ?? ''));
        $password = (string) ($data['contrasena'] ?? $data['password'] ?? '');
        $plan = !empty($data['plan_personalizado']) ? 1 : 0;
        
        // Ahora dieta puede ser un id_dieta en lugar de un booleano (0 si es false/vacio)
        $id_dieta = !empty($data['dieta']) ? (int) $data['dieta'] : null;

        if ($nombre === '' || $cedula === '' || $password === '') {
            $this->jsonResponse(['ok' => false, 'error' => 'faltan_datos'], 422);
        }

        $userId = $this->model->addUser($nombre, $cedula, $password, $plan, $id_dieta);

        $this->jsonResponse(['ok' => true, 'id_usuario' => $userId], 201);
    }

    /**
     * Obtiene todos los usuarios.
     */
    private function getUsers(): void
    {
        $users = $this->model->getAllUsers();
        $this->jsonResponse(['ok' => true, 'users' => $users]);
    }

    /**
     * Actualiza un usuario existente.
     */
    private function updateUser(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->jsonResponse(['ok' => false, 'error' => 'id_invalido'], 422);
        }

        $data = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($data)) {
            $this->jsonResponse(['ok' => false, 'error' => 'datos_invalidos'], 400);
        }

        $nombre = trim((string) ($data['nombre'] ?? ''));
        $cedula = trim((string) ($data['cedula'] ?? ''));
        $password = (string) ($data['contrasena'] ?? '');
        $plan = !empty($data['plan_personalizado']) ? 1 : 0;
        $id_dieta = !empty($data['dieta']) ? 1 : 0; // The frontend sends 1 or 0 for true/false right now, but ideally id_dieta.
        
        if ($nombre === '' || $cedula === '') {
            $this->jsonResponse(['ok' => false, 'error' => 'faltan_datos'], 422);
        }

        $this->model->updateUser($id, $nombre, $cedula, $password, $plan, $id_dieta);
        $this->jsonResponse(['ok' => true]);
    }

    /**
     * Elimina un usuario por su ID.
     */
    private function deleteUser(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->jsonResponse(['ok' => false, 'error' => 'id_invalido'], 422);
        }

        $this->model->deleteUser($id);
        $this->jsonResponse(['ok' => true]);
    }

    /**
     * Obtiene el catálogo de ejercicios.
     */
    private function getEjercicios(): void
    {
        $ejercicios = $this->model->getAllEjercicios();
        $this->jsonResponse(['ok' => true, 'ejercicios' => $ejercicios]);
    }

    /**
     * Obtiene la rutina personalizada de un usuario.
     */
    private function getRutinaPersonalizada(): void
    {
        $userId = (int) ($_GET['user_id'] ?? 0);
        if ($userId <= 0) {
            $this->jsonResponse(['ok' => false, 'error' => 'usuario_invalido'], 422);
        }

        $dias = $this->model->getRutinaPersonalizada($userId);
        $this->jsonResponse(['ok' => true, 'dias' => $dias]);
    }

    /**
     * Guarda la rutina personalizada de un usuario.
     */
    private function saveRutinaPersonalizada(): void
    {
        $userId = (int) ($_GET['user_id'] ?? 0);
        if ($userId <= 0) {
            $this->jsonResponse(['ok' => false, 'error' => 'usuario_invalido'], 422);
        }

        $data = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($data) || !isset($data['dias'])) {
            $this->jsonResponse(['ok' => false, 'error' => 'datos_invalidos'], 400);
        }

        $this->model->saveRutinaPersonalizada($userId, $data['dias']);
        $this->jsonResponse(['ok' => true]);
    }

    /**
     * Obtiene todas las máquinas.
     */
    private function getMachines(): void
    {
        $machines = $this->model->getAllMachines();
        $this->jsonResponse(['ok' => true, 'machines' => $machines]);
    }

    /**
     * Agrega una nueva máquina.
     */
    private function addMachine(): void
    {
        $data = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($data)) {
            $this->jsonResponse(['ok' => false, 'error' => 'datos_invalidos'], 400);
        }

        $nombre = trim((string) ($data['nombre'] ?? ''));
        $categoria = trim((string) ($data['categoria'] ?? ''));
        $descripcion = trim((string) ($data['descripcion'] ?? ''));
        $ubicacion = trim((string) ($data['ubicacion'] ?? ''));
        $foto = isset($data['foto']) ? (string) $data['foto'] : null;

        if ($nombre === '') {
            $this->jsonResponse(['ok' => false, 'error' => 'faltan_datos'], 422);
        }

        $id = $this->model->addMachine($nombre, $categoria, $descripcion, $ubicacion, $foto);
        $this->jsonResponse(['ok' => true, 'id' => $id]);
    }

    /**
     * Actualiza una máquina existente.
     */
    private function updateMachine(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->jsonResponse(['ok' => false, 'error' => 'id_invalido'], 422);
        }

        $data = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($data)) {
            $this->jsonResponse(['ok' => false, 'error' => 'datos_invalidos'], 400);
        }

        $nombre = trim((string) ($data['nombre'] ?? ''));
        $categoria = trim((string) ($data['categoria'] ?? ''));
        $descripcion = trim((string) ($data['descripcion'] ?? ''));
        $ubicacion = trim((string) ($data['ubicacion'] ?? ''));
        $foto = isset($data['foto']) ? (string) $data['foto'] : null;

        if ($nombre === '') {
            $this->jsonResponse(['ok' => false, 'error' => 'faltan_datos'], 422);
        }

        $this->model->updateMachine($id, $nombre, $categoria, $descripcion, $ubicacion, $foto);
        $this->jsonResponse(['ok' => true]);
    }

    /**
     * Elimina una máquina.
     */
    private function deleteMachine(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->jsonResponse(['ok' => false, 'error' => 'id_invalido'], 422);
        }

        $this->model->deleteMachine($id);
        $this->jsonResponse(['ok' => true]);
    }
}
