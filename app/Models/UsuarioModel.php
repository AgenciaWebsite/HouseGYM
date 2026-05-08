<?php
declare(strict_types=1);

namespace App\Models;

use PDO;
use App\Core\Database;

/**
 * Clase UsuarioModel
 * 
 * Gestiona todas las operaciones de base de datos relacionadas con el dashboard del usuario.
 */
class UsuarioModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Obtiene los datos básicos del perfil del usuario y su estado de plan/dieta.
     */
    public function getUserProfile(int $userId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT u.id_usuario, u.nombre, u.cedula, u.activo, u.plan_personalizado, rp.id_dieta
             FROM usuarios u
             LEFT JOIN rutina_personalizada rp ON u.id_usuario = rp.id_usuario AND rp.activa = 1
             WHERE u.id_usuario = ? LIMIT 1'
        );
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Obtiene la rutina personalizada de un usuario agrupada por días.
     */
    public function getUserRoutine(int $userId): array
    {
        // Obtener el ID de la rutina personalizada activa para el usuario
        $stmt = $this->pdo->prepare('SELECT id_rutina_pers FROM rutina_personalizada WHERE id_usuario = ? LIMIT 1');
        $stmt->execute([$userId]);
        $rutinaId = $stmt->fetchColumn();

        if (!$rutinaId) {
            return []; // El usuario no tiene rutina asignada
        }

        // Obtener los detalles agrupados por dia
        $stmt = $this->pdo->prepare(
            'SELECT d.dia, d.id_ejercicio, d.series, d.repeticiones as reps,
                    e.nombre, e.imagen_url, e.grupo_muscular
             FROM rutina_personalizada_detalle d
             JOIN ejercicios e ON d.id_ejercicio = e.id_ejercicio
             WHERE d.id_rutina_pers = ?
             ORDER BY d.dia ASC, d.id ASC'
        );
        $stmt->execute([$rutinaId]);
        $rows = $stmt->fetchAll();

        $diasMap = [];
        foreach ($rows as $row) {
            $diaIndex = (int)$row['dia'] - 1; 
            if (!isset($diasMap[$diaIndex])) {
                $diasMap[$diaIndex] = ['ejercicios' => []];
            }
            $diasMap[$diaIndex]['ejercicios'][] = [
                'id_ejercicio' => $row['id_ejercicio'],
                'nombre' => $row['nombre'],
                'imagen_url' => $row['imagen_url'],
                'grupo_muscular' => $row['grupo_muscular'],
                'reps' => $row['reps'],
                'series' => $row['series']
            ];
        }

        $maxDia = empty($diasMap) ? -1 : max(array_keys($diasMap));
        $result = [];
        for ($i = 0; $i <= $maxDia; $i++) {
            $result[] = isset($diasMap[$i]) ? $diasMap[$i] : ['ejercicios' => []];
        }

        return $result;
    }

    /**
     * Obtiene todas las máquinas disponibles en el gimnasio (solo lectura).
     */
    public function getAllMaquinas(): array
    {
        return $this->pdo->query(
            'SELECT id_maquina, nombre, descripcion, foto_url AS foto, categoria, ubicacion
             FROM maquinas
             ORDER BY nombre ASC'
        )->fetchAll();
    }
    /**
     * Obtiene todos los ejercicios del catálogo (solo lectura).
     */
    public function getAllEjercicios(): array
    {
        return $this->pdo->query(
            'SELECT e.id_ejercicio, e.nombre, e.descripcion, e.foto_url AS imagen_url,
                    e.id_grupo, e.id_maquina, g.nombre AS grupo_muscular
             FROM ejercicios e
             JOIN grupo_muscular g ON g.id_grupo = e.id_grupo
             ORDER BY e.nombre ASC'
        )->fetchAll();
    }
}
