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
            'SELECT u.id_usuario, u.nombre, u.cedula, u.activo, u.plan_personalizado, u.genero, rp.id_dieta
             FROM usuarios u
             LEFT JOIN rutina_personalizada rp ON u.id_usuario = rp.id_usuario AND rp.activa = 1
             WHERE u.id_usuario = ? LIMIT 1'
        );
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Obtiene los detalles de la dieta asignada.
     */
    public function getUserDiet(int $idDieta): ?array
    {
        $stmt = $this->pdo->prepare('SELECT tipo, descripcion, foto_url FROM dietas WHERE id_dieta = ? LIMIT 1');
        $stmt->execute([$idDieta]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Obtiene la rutina personalizada de un usuario agrupada por días.
     */
    public function getUserRoutine(int $userId): array
    {
        // Obtener el ID de la rutina personalizada activa para el usuario
        $stmt = $this->pdo->prepare(
            'SELECT id_rutina_pers FROM rutina_personalizada
             WHERE id_usuario = ? AND activa = 1
             ORDER BY id_rutina_pers DESC LIMIT 1'
        );
        $stmt->execute([$userId]);
        $rutinaId = $stmt->fetchColumn();

        if (!$rutinaId) {
            return []; // El usuario no tiene rutina asignada o no está activa
        }

        // Obtener los detalles agrupados por día, con JOIN correcto a grupo_muscular
        $stmt = $this->pdo->prepare(
            'SELECT d.dia, d.id_ejercicio, d.series, d.repeticiones AS reps,
                    e.nombre, e.foto_url AS imagen_url, g.nombre AS grupo_muscular, m.nombre AS maquina
             FROM rutina_personalizada_detalle d
             JOIN ejercicios e ON e.id_ejercicio = d.id_ejercicio
             JOIN grupo_muscular g ON g.id_grupo = e.id_grupo
             LEFT JOIN maquinas m ON m.id_maquina = e.id_maquina
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
                'id_ejercicio'   => (int)$row['id_ejercicio'],
                'nombre'         => $row['nombre'],
                'imagen_url'     => $row['imagen_url'],
                'grupo_muscular' => $row['grupo_muscular'],
                'maquina'        => $row['maquina'],
                'reps'           => (int)$row['reps'],
                'series'         => (int)$row['series'],
            ];
        }

        $maxDia = empty($diasMap) ? -1 : max(array_keys($diasMap));
        $result = [];
        for ($i = 0; $i <= $maxDia; $i++) {
            $result[] = $diasMap[$i] ?? ['ejercicios' => []];
        }

        return $result;
    }

    /**
     * Obtiene la rutina global por género y semana (5 días fijos).
     */
    public function getGlobalRoutine(string $genero, int $semana): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id_rutina_global FROM rutina_global
             WHERE genero = ? AND semana = ? AND activa = 1
             ORDER BY id_rutina_global DESC LIMIT 1'
        );
        $stmt->execute([$genero, $semana]);
        $rutinaId = $stmt->fetchColumn();

        if (!$rutinaId) {
            return [];
        }

        $stmt = $this->pdo->prepare(
            'SELECT d.dia, d.id_ejercicio, d.series, d.repeticiones AS reps,
                    e.nombre, e.foto_url AS imagen_url, g.nombre AS grupo_muscular, m.nombre AS maquina
             FROM rutina_global_detalle d
             JOIN ejercicios e ON e.id_ejercicio = d.id_ejercicio
             JOIN grupo_muscular g ON g.id_grupo = e.id_grupo
             LEFT JOIN maquinas m ON m.id_maquina = e.id_maquina
             WHERE d.id_rutina_global = ?
             ORDER BY d.dia ASC, d.orden ASC'
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
                'id_ejercicio'   => (int)$row['id_ejercicio'],
                'nombre'         => $row['nombre'],
                'imagen_url'     => $row['imagen_url'],
                'grupo_muscular' => $row['grupo_muscular'],
                'maquina'        => $row['maquina'],
                'reps'           => (int)$row['reps'],
                'series'         => (int)$row['series'],
            ];
        }

        $result = [];
        for ($i = 0; $i < 5; $i++) {
            $result[] = $diasMap[$i] ?? ['ejercicios' => []];
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
                    e.id_grupo, e.id_maquina, g.nombre AS grupo_muscular, m.nombre AS maquina
             FROM ejercicios e
             JOIN grupo_muscular g ON g.id_grupo = e.id_grupo
             LEFT JOIN maquinas m ON m.id_maquina = e.id_maquina
             ORDER BY e.nombre ASC'
        )->fetchAll();
    }
}
