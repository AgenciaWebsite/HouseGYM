<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\UsuarioModel;
use Throwable;

/**
 * Clase UsuarioController
 * 
 * Gestiona las peticiones (API) para el panel personal de los usuarios.
 */
class UsuarioController
{
    private UsuarioModel $model;

    public function __construct()
    {
        $this->model = new UsuarioModel();
    }

    /**
     * Enruta la petición según el recurso solicitado.
     */
    public function handleRequest(string $method, string $resource): void
    {
        header('Content-Type: application/json');

        // Verificación de sesión: Solo un "usuario" logueado puede acceder a esta API
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['usuario_id']) || ($_SESSION['rol'] ?? '') !== 'usuario') {
            http_response_code(401);
            echo json_encode(['ok' => false, 'error' => 'No autorizado']);
            exit;
        }

        $userId = (int) $_SESSION['usuario_id'];

        try {
            switch ($resource) {
                case 'profile':
                    $this->getProfile($method, $userId);
                    break;
                case 'routine':
                    $this->getRoutine($method, $userId);
                    break;
                case 'global_routine':
                    $this->getGlobalRoutine($method, $userId);
                    break;
                case 'diet':
                    $this->getDiet($method, $userId);
                    break;
                case 'machines':
                    $this->getMachines($method);
                    break;
                case 'ejercicios':
                    $this->getEjercicios($method);
                    break;
                default:
                    http_response_code(404);
                    echo json_encode(['ok' => false, 'error' => 'Recurso no encontrado']);
                    break;
            }
        } catch (Throwable $e) {
            error_log('Error en UsuarioController: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => 'Error interno del servidor']);
        }
    }

    private function getProfile(string $method, int $userId): void
    {
        if ($method !== 'GET') {
            $this->sendMethodNotAllowed();
        }
        
        $profile = $this->model->getUserProfile($userId);
        
        if (!$profile) {
            echo json_encode(['ok' => false, 'error' => 'Usuario no encontrado']);
            return;
        }

        echo json_encode([
            'ok' => true,
            'profile' => [
                'nombre' => $profile['nombre'],
                'cedula' => $profile['cedula'],
                'plan_personalizado' => (int) $profile['plan_personalizado'] === 1,
                'id_dieta' => $profile['id_dieta'] ? (int) $profile['id_dieta'] : null,
                'activo' => (int) $profile['activo'] === 1
            ]
        ]);
    }

    private function getRoutine(string $method, int $userId): void
    {
        if ($method !== 'GET') {
            $this->sendMethodNotAllowed();
        }

        $routine = $this->model->getUserRoutine($userId);

        echo json_encode([
            'ok' => true,
            'routine' => $routine
        ]);
    }

    private function getGlobalRoutine(string $method, int $userId): void
    {
        if ($method !== 'GET') {
            $this->sendMethodNotAllowed();
        }

        $semana = isset($_GET['semana']) ? (int)$_GET['semana'] : 1;
        if ($semana < 1 || $semana > 4) {
            $semana = 1;
        }

        $profile = $this->model->getUserProfile($userId);
        $genero = $profile['genero'] ?? 'Hombre';

        $routine = $this->model->getGlobalRoutine($genero, $semana);

        echo json_encode([
            'ok' => true,
            'routine' => $routine
        ]);
    }

    private function getDiet(string $method, int $userId): void
    {
        if ($method !== 'GET') {
            $this->sendMethodNotAllowed();
        }

        $profile = $this->model->getUserProfile($userId);
        
        if (!$profile || empty($profile['id_dieta'])) {
            echo json_encode(['ok' => true, 'diet' => null]);
            return;
        }

        $dietDetails = $this->model->getUserDiet((int)$profile['id_dieta']);

        echo json_encode([
            'ok' => true,
            'diet' => [
                'id_dieta'    => (int)$profile['id_dieta'],
                'nombre'      => $dietDetails ? $dietDetails['tipo'] : 'Personalizada',
                'descripcion' => $dietDetails ? $dietDetails['descripcion'] : '',
                'foto_url'    => $dietDetails ? ($dietDetails['foto_url'] ?? null) : null,
            ]
        ]);
    }

    private function getMachines(string $method): void
    {
        if ($method !== 'GET') {
            $this->sendMethodNotAllowed();
        }

        $machines = $this->model->getAllMaquinas();
        echo json_encode(['ok' => true, 'machines' => $machines]);
    }

    private function getEjercicios(string $method): void
    {
        if ($method !== 'GET') {
            $this->sendMethodNotAllowed();
        }

        $ejercicios = $this->model->getAllEjercicios();
        echo json_encode(['ok' => true, 'ejercicios' => $ejercicios]);
    }

    private function sendMethodNotAllowed(): void
    {
        http_response_code(405);
        echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
        exit;
    }
}
