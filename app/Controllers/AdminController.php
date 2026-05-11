<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\AdminModel;
use App\Core\Cloudinary;

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
                // Soporte para _method=PUT enviado como multipart
                $methodOverride = trim((string) ($_POST['_method'] ?? ''));
                if (strtoupper($methodOverride) === 'PUT') {
                    $this->updateEjercicio();
                } else {
                    $this->addEjercicio();
                }
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
                // El frontend puede enviar _method=PUT en el body multipart para indicar actualización
                $methodOverride = trim((string) ($_POST['_method'] ?? ''));
                if (strtoupper($methodOverride) === 'PUT') {
                    $this->updateMachine();
                } else {
                    $this->addMachine();
                }
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
                // Soporte para _method=PUT enviado como multipart
                $methodOverride = trim((string) ($_POST['_method'] ?? ''));
                if (strtoupper($methodOverride) === 'PUT') {
                    $this->updateDieta();
                } else {
                    $this->addDieta();
                }
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
            $this->jsonResponse(['ok' => false, 'error' => 'base_de_datos', 'msg' => $e->getMessage()], $status);
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
     * Recibe multipart/form-data. Si viene una foto, la sube a Cloudinary.
     */
    private function addEjercicio(): void
    {
        $nombre      = trim((string) ($_POST['nombre']      ?? ''));
        $id_grupo    = !empty($_POST['id_grupo'])   ? (int) $_POST['id_grupo']   : null;
        $id_maquina  = !empty($_POST['id_maquina']) ? (int) $_POST['id_maquina'] : null;
        $descripcion = trim((string) ($_POST['descripcion'] ?? ''));

        if ($nombre === '') {
            $this->jsonResponse(['ok' => false, 'error' => 'faltan_datos'], 422);
        }

        $fotoUrl = $this->uploadEjercicioPhoto();

        $id = $this->model->addEjercicio($nombre, $id_grupo, $id_maquina, $descripcion, $fotoUrl);
        $this->jsonResponse(['ok' => true, 'id' => $id, 'foto_url' => $fotoUrl]);
    }

    /**
     * Actualiza un ejercicio.
     * Recibe multipart/form-data. Si viene una foto nueva, la sube a Cloudinary.
     */
    private function updateEjercicio(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->jsonResponse(['ok' => false, 'error' => 'id_invalido'], 422);
        }

        $nombre      = trim((string) ($_POST['nombre']      ?? ''));
        $id_grupo    = !empty($_POST['id_grupo'])   ? (int) $_POST['id_grupo']   : null;
        $id_maquina  = !empty($_POST['id_maquina']) ? (int) $_POST['id_maquina'] : null;
        $descripcion = trim((string) ($_POST['descripcion'] ?? ''));

        if ($nombre === '') {
            $this->jsonResponse(['ok' => false, 'error' => 'faltan_datos'], 422);
        }

        $fotoUrl = $this->uploadEjercicioPhoto(); // null si no hay foto nueva

        $this->model->updateEjercicio($id, $nombre, $id_grupo, $id_maquina, $descripcion, $fotoUrl);
        $this->jsonResponse(['ok' => true, 'foto_url' => $fotoUrl]);
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
     * Sube la foto de ejercicio a Cloudinary si viene en $_FILES['foto'].
     *
     * @return string|null URL segura de Cloudinary, o null si no hay archivo.
     */
    private function uploadEjercicioPhoto(): ?string
    {
        if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $file = $_FILES['foto'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $phpErrors = [
                UPLOAD_ERR_INI_SIZE   => 'La imagen supera el límite configurado en el servidor (upload_max_filesize).',
                UPLOAD_ERR_FORM_SIZE  => 'La imagen supera el límite del formulario.',
                UPLOAD_ERR_PARTIAL    => 'La imagen se subió parcialmente. Intenta de nuevo.',
                UPLOAD_ERR_NO_TMP_DIR => 'No hay directorio temporal en el servidor.',
                UPLOAD_ERR_CANT_WRITE => 'Error al escribir el archivo temporal.',
                UPLOAD_ERR_EXTENSION  => 'Una extensión de PHP bloqueó la subida.',
            ];
            $msg = $phpErrors[$file['error']] ?? "Error de subida PHP #{$file['error']}.";
            $this->jsonResponse(['ok' => false, 'error' => 'php_upload_error', 'msg' => $msg], 422);
        }

        if ($file['size'] > 10 * 1024 * 1024) {
            $this->jsonResponse(['ok' => false, 'error' => 'foto_demasiado_grande', 'msg' => 'La imagen no puede superar 10 MB.'], 422);
        }

        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp', 'image/gif'], true)) {
            $this->jsonResponse(['ok' => false, 'error' => 'tipo_invalido', 'msg' => 'Solo se permiten imágenes (JPG, PNG, WEBP, GIF).'], 422);
        }

        try {
            $cloudinary = new Cloudinary();
            $publicId   = 'ejercicio_' . uniqid('', true);
            return $cloudinary->upload($file['tmp_name'], $publicId);
        } catch (\Throwable $e) {
            $this->jsonResponse(['ok' => false, 'error' => 'cloudinary_error', 'msg' => $e->getMessage()], 500);
        }

        return null;
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
     * Recibe multipart/form-data. Si viene una foto, la sube a Cloudinary
     * y guarda la URL segura en la base de datos.
     */
    private function addMachine(): void
    {
        // Leer campos de texto del formulario multipart
        $nombre      = trim((string) ($_POST['nombre']      ?? ''));
        $categoria   = trim((string) ($_POST['categoria']   ?? ''));
        $descripcion = trim((string) ($_POST['descripcion'] ?? ''));
        $ubicacion   = trim((string) ($_POST['ubicacion']   ?? ''));

        if ($nombre === '') {
            $this->jsonResponse(['ok' => false, 'error' => 'faltan_datos'], 422);
        }

        $fotoUrl = $this->uploadMachinePhoto();

        $id = $this->model->addMachine($nombre, $categoria, $descripcion, $ubicacion, $fotoUrl);

        // Devolver también la URL para que el frontend actualice la card sin recargar
        $this->jsonResponse(['ok' => true, 'id' => $id, 'foto_url' => $fotoUrl]);
    }

    /**
     * Actualiza una máquina existente.
     * Recibe multipart/form-data. Si viene una foto nueva, la sube a Cloudinary.
     */
    private function updateMachine(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->jsonResponse(['ok' => false, 'error' => 'id_invalido'], 422);
        }

        // PHP no llena $_POST con PUT/PATCH multipart; usamos POST con _method override
        // o bien el frontend manda PUT como POST con campo ?_method=PUT.
        // Para simplificar el cliente usa POST para crear/actualizar.
        $nombre      = trim((string) ($_POST['nombre']      ?? ''));
        $categoria   = trim((string) ($_POST['categoria']   ?? ''));
        $descripcion = trim((string) ($_POST['descripcion'] ?? ''));
        $ubicacion   = trim((string) ($_POST['ubicacion']   ?? ''));

        if ($nombre === '') {
            $this->jsonResponse(['ok' => false, 'error' => 'faltan_datos'], 422);
        }

        // Solo subir foto si el usuario seleccionó una nueva
        $fotoUrl = $this->uploadMachinePhoto(); // null si no hay archivo

        $this->model->updateMachine($id, $nombre, $categoria, $descripcion, $ubicacion, $fotoUrl);
        $this->jsonResponse(['ok' => true, 'foto_url' => $fotoUrl]);
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
     * Sube la foto de máquina a Cloudinary si viene en $_FILES['foto'].
     *
     * @return string|null URL segura de Cloudinary, o null si no hay archivo.
     */
    private function uploadMachinePhoto(): ?string
    {
        // Sin archivo adjunto
        if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $file = $_FILES['foto'];

        // Manejar errores de PHP antes de procesar
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $phpErrors = [
                UPLOAD_ERR_INI_SIZE   => 'La imagen supera el límite configurado en el servidor (upload_max_filesize).',
                UPLOAD_ERR_FORM_SIZE  => 'La imagen supera el límite del formulario.',
                UPLOAD_ERR_PARTIAL    => 'La imagen se subió parcialmente. Intenta de nuevo.',
                UPLOAD_ERR_NO_TMP_DIR => 'No hay directorio temporal en el servidor.',
                UPLOAD_ERR_CANT_WRITE => 'Error al escribir el archivo temporal.',
                UPLOAD_ERR_EXTENSION  => 'Una extensión de PHP bloqueó la subida.',
            ];
            $msg = $phpErrors[$file['error']] ?? "Error de subida PHP #{$file['error']}.";
            $this->jsonResponse(['ok' => false, 'error' => 'php_upload_error', 'msg' => $msg], 422);
        }

        // Validar tamaño máximo: 10 MB
        if ($file['size'] > 10 * 1024 * 1024) {
            $this->jsonResponse(['ok' => false, 'error' => 'foto_demasiado_grande', 'msg' => 'La imagen no puede superar 10 MB.'], 422);
        }

        // Validar que sea imagen real
        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp', 'image/gif'], true)) {
            $this->jsonResponse(['ok' => false, 'error' => 'tipo_invalido', 'msg' => 'Solo se permiten imágenes (JPG, PNG, WEBP, GIF).'], 422);
        }

        try {
            $cloudinary = new Cloudinary();
            $publicId   = 'maquina_' . uniqid('', true);
            return $cloudinary->upload($file['tmp_name'], $publicId);
        } catch (\Throwable $e) {
            $this->jsonResponse(['ok' => false, 'error' => 'cloudinary_error', 'msg' => $e->getMessage()], 500);
        }

        return null; // Nunca se alcanza, pero satisface el analizador estático
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
     * Recibe multipart/form-data. Si viene una foto, la sube a Cloudinary.
     */
    private function addDieta(): void
    {
        $tipo        = trim((string) ($_POST['tipo']        ?? ''));
        $descripcion = trim((string) ($_POST['descripcion'] ?? ''));

        if ($tipo === '') {
            $this->jsonResponse(['ok' => false, 'error' => 'faltan_datos'], 422);
        }

        $fotoUrl = $this->uploadDietaPhoto();

        $id = $this->model->addDieta($tipo, $descripcion, $fotoUrl);
        $this->jsonResponse(['ok' => true, 'id' => $id, 'foto_url' => $fotoUrl]);
    }

    /**
     * Actualiza una dieta existente.
     * Recibe multipart/form-data. Si viene una foto nueva, la sube a Cloudinary.
     */
    private function updateDieta(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->jsonResponse(['ok' => false, 'error' => 'id_invalido'], 422);
        }

        $tipo        = trim((string) ($_POST['tipo']        ?? ''));
        $descripcion = trim((string) ($_POST['descripcion'] ?? ''));

        if ($tipo === '') {
            $this->jsonResponse(['ok' => false, 'error' => 'faltan_datos'], 422);
        }

        $fotoUrl = $this->uploadDietaPhoto(); // null si no hay foto nueva

        $this->model->updateDieta($id, $tipo, $descripcion, $fotoUrl);
        $this->jsonResponse(['ok' => true, 'foto_url' => $fotoUrl]);
    }

    /**
     * Sube la foto de dieta a Cloudinary si viene en $_FILES['foto'].
     *
     * @return string|null URL segura de Cloudinary, o null si no hay archivo.
     */
    private function uploadDietaPhoto(): ?string
    {
        if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $file = $_FILES['foto'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $phpErrors = [
                UPLOAD_ERR_INI_SIZE   => 'La imagen supera el límite configurado en el servidor (upload_max_filesize).',
                UPLOAD_ERR_FORM_SIZE  => 'La imagen supera el límite del formulario.',
                UPLOAD_ERR_PARTIAL    => 'La imagen se subió parcialmente. Intenta de nuevo.',
                UPLOAD_ERR_NO_TMP_DIR => 'No hay directorio temporal en el servidor.',
                UPLOAD_ERR_CANT_WRITE => 'Error al escribir el archivo temporal.',
                UPLOAD_ERR_EXTENSION  => 'Una extensión de PHP bloqueó la subida.',
            ];
            $msg = $phpErrors[$file['error']] ?? "Error de subida PHP #{$file['error']}.";
            $this->jsonResponse(['ok' => false, 'error' => 'php_upload_error', 'msg' => $msg], 422);
        }

        if ($file['size'] > 10 * 1024 * 1024) {
            $this->jsonResponse(['ok' => false, 'error' => 'foto_demasiado_grande', 'msg' => 'La imagen no puede superar 10 MB.'], 422);
        }

        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp', 'image/gif'], true)) {
            $this->jsonResponse(['ok' => false, 'error' => 'tipo_invalido', 'msg' => 'Solo se permiten imágenes (JPG, PNG, WEBP, GIF).'], 422);
        }

        try {
            $cloudinary = new Cloudinary();
            $publicId   = 'dieta_' . uniqid('', true);
            return $cloudinary->upload($file['tmp_name'], $publicId);
        } catch (\Throwable $e) {
            $this->jsonResponse(['ok' => false, 'error' => 'cloudinary_error', 'msg' => $e->getMessage()], 500);
        }

        return null;
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
