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

            if ($method === 'POST' && $resource === 'ejercicios') {
                $this->addEjercicio();
            }

            if ($method === 'PUT' && $resource === 'ejercicios') {
                $this->updateEjercicio();
            }

            if ($method === 'DELETE' && $resource === 'ejercicios') {
                $this->deleteEjercicio();
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

            if ($method === 'GET' && $resource === 'rutina_global') {
                $this->getRutinaGlobal();
            }

            if ($method === 'POST' && $resource === 'rutina_global') {
                $this->saveRutinaGlobal();
            }

            if ($method === 'GET' && $resource === 'dietas') {
                $this->getDietas();
            }

            if ($method === 'POST' && $resource === 'dietas') {
                $this->addDieta();
            }

            if ($method === 'PUT' && $resource === 'dietas') {
                $this->updateDieta();
            }

            if ($method === 'DELETE' && $resource === 'dietas') {
                $this->deleteDieta();
            }

            if ($method === 'GET' && $resource === 'dieta_usuario') {
                $this->getDietaUsuario();
            }

            if ($method === 'POST' && $resource === 'dieta_usuario') {
                $this->saveDietaUsuario();
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
        $genero = trim((string) ($data['genero'] ?? 'Hombre')); // Default Hombre

        // Ahora dieta puede ser un id_dieta en lugar de un booleano (0 si es false/vacio)
        $id_dieta = !empty($data['dieta']) ? (int) $data['dieta'] : null;

        if ($nombre === '' || $cedula === '' || $password === '') {
            $this->jsonResponse(['ok' => false, 'error' => 'faltan_datos'], 422);
        }

        $userId = $this->model->addUser($nombre, $cedula, $password, $plan, $id_dieta, $genero);

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
        $id_dieta = !empty($data['dieta']) ? (int) $data['dieta'] : 0;

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
     * Agrega un nuevo ejercicio.
     */
    private function addEjercicio(): void
    {
        $data = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($data)) {
            $this->jsonResponse(['ok' => false, 'error' => 'datos_invalidos'], 400);
        }

        $nombre = trim((string) ($data['nombre'] ?? ''));
        $id_grupo = !empty($data['id_grupo']) ? (int) $data['id_grupo'] : null;
        $id_maquina = !empty($data['id_maquina']) ? (int) $data['id_maquina'] : null;
        $descripcion = trim((string) ($data['descripcion'] ?? ''));
        $foto = isset($data['foto']) ? (string) $data['foto'] : null;

        if ($nombre === '') {
            $this->jsonResponse(['ok' => false, 'error' => 'faltan_datos'], 422);
        }

        $id = $this->model->addEjercicio($nombre, $id_grupo, $id_maquina, $descripcion, $foto);
        $this->jsonResponse(['ok' => true, 'id' => $id]);
    }

    /**
     * Actualiza un ejercicio.
     */
    private function updateEjercicio(): void
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
        $id_grupo = !empty($data['id_grupo']) ? (int) $data['id_grupo'] : null;
        $id_maquina = !empty($data['id_maquina']) ? (int) $data['id_maquina'] : null;
        $descripcion = trim((string) ($data['descripcion'] ?? ''));
        $foto = isset($data['foto']) ? (string) $data['foto'] : null;

        if ($nombre === '') {
            $this->jsonResponse(['ok' => false, 'error' => 'faltan_datos'], 422);
        }

        $this->model->updateEjercicio($id, $nombre, $id_grupo, $id_maquina, $descripcion, $foto);
        $this->jsonResponse(['ok' => true]);
    }

    /**
     * Elimina un ejercicio.
     */
    private function deleteEjercicio(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->jsonResponse(['ok' => false, 'error' => 'id_invalido'], 422);
        }

        $this->model->deleteEjercicio($id);
        $this->jsonResponse(['ok' => true]);
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

    /**
     * Obtiene la rutina global de un género/semana.
     * Params GET: genero (Hombre|Mujer), semana (1-4)
     */
    private function getRutinaGlobal(): void
    {
        $genero = (string) ($_GET['genero'] ?? 'Hombre');
        $semana = (int) ($_GET['semana'] ?? 1);

        if (!in_array($genero, ['Hombre', 'Mujer'], true)) {
            $this->jsonResponse(['ok' => false, 'error' => 'genero_invalido'], 422);
        }
        if ($semana < 1 || $semana > 4) {
            $this->jsonResponse(['ok' => false, 'error' => 'semana_invalida'], 422);
        }

        $dias = $this->model->getRutinaGlobal($genero, $semana);
        $this->jsonResponse(['ok' => true, 'dias' => $dias]);
    }

    /**
     * Guarda la rutina global de un género/semana.
     * Body JSON: { genero, semana, dias: [{dia, ejercicios:[{id_ejercicio,series,reps}]}] }
     */
    private function saveRutinaGlobal(): void
    {
        $data = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($data)) {
            $this->jsonResponse(['ok' => false, 'error' => 'datos_invalidos'], 400);
        }

        $genero = (string) ($data['genero'] ?? '');
        $semana = (int) ($data['semana'] ?? 0);
        $dias = $data['dias'] ?? [];

        if (!in_array($genero, ['Hombre', 'Mujer'], true)) {
            $this->jsonResponse(['ok' => false, 'error' => 'genero_invalido'], 422);
        }
        if ($semana < 1 || $semana > 4) {
            $this->jsonResponse(['ok' => false, 'error' => 'semana_invalida'], 422);
        }
        if (!is_array($dias)) {
            $this->jsonResponse(['ok' => false, 'error' => 'datos_invalidos'], 400);
        }

        $this->model->saveRutinaGlobal($genero, $semana, $dias);
        $this->jsonResponse(['ok' => true]);
    }

    /**
     * Obtiene todas las dietas.
     */
    private function getDietas(): void
    {
        $dietas = $this->model->getAllDietas();
        $this->jsonResponse(['ok' => true, 'dietas' => $dietas]);
    }

    /**
     * Agrega una nueva dieta.
     */
    private function addDieta(): void
    {
        $data = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($data)) {
            $this->jsonResponse(['ok' => false, 'error' => 'datos_invalidos'], 400);
        }

        $tipo = trim((string) ($data['tipo'] ?? ''));
        $descripcion = trim((string) ($data['descripcion'] ?? ''));
        $foto = isset($data['foto']) ? (string) $data['foto'] : null;

        if ($tipo === '') {
            $this->jsonResponse(['ok' => false, 'error' => 'faltan_datos'], 422);
        }

        $id = $this->model->addDieta($tipo, $descripcion, $foto);
        $this->jsonResponse(['ok' => true, 'id' => $id]);
    }

    /**
     * Actualiza una dieta existente.
     */
    private function updateDieta(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->jsonResponse(['ok' => false, 'error' => 'id_invalido'], 422);
        }

        $data = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($data)) {
            $this->jsonResponse(['ok' => false, 'error' => 'datos_invalidos'], 400);
        }

        $tipo = trim((string) ($data['tipo'] ?? ''));
        $descripcion = trim((string) ($data['descripcion'] ?? ''));
        $foto = isset($data['foto']) ? (string) $data['foto'] : null;

        if ($tipo === '') {
            $this->jsonResponse(['ok' => false, 'error' => 'faltan_datos'], 422);
        }

        $this->model->updateDieta($id, $tipo, $descripcion, $foto);
        $this->jsonResponse(['ok' => true]);
    }

    /**
     * Elimina una dieta.
     */
    private function deleteDieta(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->jsonResponse(['ok' => false, 'error' => 'id_invalido'], 422);
        }

        $this->model->deleteDieta($id);
        $this->jsonResponse(['ok' => true]);
    }

    /**
     * Obtiene la dieta asignada a un usuario.
     */
    private function getDietaUsuario(): void
    {
        $userId = (int) ($_GET['user_id'] ?? 0);
        if ($userId <= 0) {
            $this->jsonResponse(['ok' => false, 'error' => 'user_id_invalido'], 422);
        }

        $diet = $this->model->getDietaUsuario($userId);
        $this->jsonResponse(['ok' => true, 'diet' => $diet]);
    }

    /**
     * Guarda o actualiza la dieta asignada a un usuario.
     */
    private function saveDietaUsuario(): void
    {
        $userId = (int) ($_GET['user_id'] ?? 0);
        if ($userId <= 0) {
            $this->jsonResponse(['ok' => false, 'error' => 'user_id_invalido'], 422);
        }

        $data = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($data) || !array_key_exists('id_dieta', $data)) {
            $this->jsonResponse(['ok' => false, 'error' => 'datos_invalidos'], 400);
        }

        $idDieta = $data['id_dieta'] ? (int) $data['id_dieta'] : null;

        $this->model->saveDietaUsuario($userId, $idDieta);
        $this->jsonResponse(['ok' => true]);
    }
}
