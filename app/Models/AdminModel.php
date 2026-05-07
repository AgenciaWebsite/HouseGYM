<?php
declare(strict_types=1);

namespace App\Models;

use PDO;
use App\Core\Database;

/**
 * Clase AdminModel
 * 
 * Gestiona todas las operaciones de base de datos relacionadas con el panel de administración,
 * estadísticas del dashboard, y gestión de usuarios.
 */
class AdminModel
{
    private PDO $pdo;

    /**
     * Constructor de AdminModel
     */
    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Obtiene estadísticas generales del sistema para el dashboard.
     *
     * @return array Estadísticas con el total de usuarios, rutinas, máquinas, dietas, etc.
     */
    public function getDashboardStats(): array
    {
        return [
            'usuarios' => $this->scalarCount('SELECT COUNT(*) FROM usuarios'),
            'rutinas_personalizadas' => $this->scalarCount('SELECT COUNT(*) FROM rutina_personalizada'),
            'maquinas' => $this->scalarCount('SELECT COUNT(*) FROM maquinas'),
            'dietas' => $this->scalarCount('SELECT COUNT(*) FROM dietas'),
            'ejercicios' => $this->scalarCount('SELECT COUNT(*) FROM ejercicios'),
            'rutinas_globales' => $this->scalarCount('SELECT COUNT(*) FROM rutina_global'),
        ];
    }

    /**
     * Obtiene los últimos usuarios registrados.
     *
     * @param int $limit Límite de resultados.
     * @return array Lista de usuarios.
     */
    public function getRecentUsers(int $limit = 5): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id_usuario, nombre, cedula, activo, plan_personalizado
             FROM usuarios
             ORDER BY id_usuario DESC
             LIMIT ?'
        );
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtiene todos los usuarios para generar la gráfica del dashboard.
     *
     * @return array Lista de usuarios.
     */
    public function getAllUsersForChart(): array
    {
        return $this->pdo->query(
            'SELECT id_usuario, cedula
             FROM usuarios
             ORDER BY id_usuario ASC'
        )->fetchAll();
    }

    /**
     * Obtiene todos los usuarios con sus detalles (para la gestión de usuarios).
     *
     * @return array Lista de usuarios.
     */
    public function getAllUsers(): array
    {
        return $this->pdo->query(
            'SELECT u.id_usuario, u.nombre, u.cedula, u.activo, u.plan_personalizado,
                    (SELECT COUNT(*) FROM rutina_personalizada rp WHERE rp.id_usuario = u.id_usuario AND rp.id_dieta IS NOT NULL AND rp.activa = 1) as dieta
             FROM usuarios u
             ORDER BY u.id_usuario DESC'
        )->fetchAll();
    }

    /**
     * Realiza una búsqueda global (usuarios, máquinas, ejercicios).
     *
     * @param string $q Término de búsqueda.
     * @return array Resultados de la búsqueda unificados.
     */
    public function searchGlobal(string $q): array
    {
        $like = '%' . $q . '%';
        $results = [];

        // Buscar usuarios
        $stmt = $this->pdo->prepare(
            "SELECT 'usuario' AS tipo, id_usuario AS id, nombre AS titulo,
                    CONCAT('CC ', cedula, IF(activo = 1, ' · Activo', ' · Inactivo')) AS detalle
             FROM usuarios
             WHERE nombre LIKE ? OR CAST(cedula AS CHAR) LIKE ?
             LIMIT 5"
        );
        $stmt->execute([$like, $like]);
        $results = array_merge($results, $stmt->fetchAll());

        // Buscar máquinas
        $stmt = $this->pdo->prepare(
            "SELECT 'maquina' AS tipo, id_maquina AS id, nombre AS titulo,
                    CONCAT('Piso ', piso, ' · Maquina') AS detalle
             FROM maquinas
             WHERE nombre LIKE ?
             LIMIT 5"
        );
        $stmt->execute([$like]);
        $results = array_merge($results, $stmt->fetchAll());

        // Buscar ejercicios
        $stmt = $this->pdo->prepare(
            "SELECT 'ejercicio' AS tipo, id_ejercicio AS id, nombre AS titulo,
                    'Ejercicio' AS detalle
             FROM ejercicios
             WHERE nombre LIKE ?
             LIMIT 5"
        );
        $stmt->execute([$like]);
        $results = array_merge($results, $stmt->fetchAll());

        return array_slice($results, 0, 10);
    }

    /**
     * Agrega un nuevo usuario al sistema con sus planes (rutina y dieta).
     *
     * @param string $nombre Nombre del usuario.
     * @param string $cedula Cédula del usuario.
     * @param string $password Contraseña (será hasheada).
     * @param int $plan_personalizado Si tiene plan personalizado (1 o 0).
     * @param int|null $id_dieta El ID de la dieta seleccionada (null si no tiene dieta).
     * @return int El ID del usuario insertado.
     */
    public function addUser(string $nombre, string $cedula, string $password, int $plan_personalizado, ?int $id_dieta): int
    {
        $this->pdo->beginTransaction();

        try {
            // Insertar usuario
            $stmt = $this->pdo->prepare(
                'INSERT INTO usuarios (nombre, cedula, contrasena, activo, plan_personalizado)
                 VALUES (?, ?, ?, 1, ?)'
            );
            $stmt->execute([$nombre, $cedula, $password, $plan_personalizado]);
            $userId = (int) $this->pdo->lastInsertId();

            // Insertar rutina si tiene plan o dieta
            if ($plan_personalizado || $id_dieta) {
                $stmt = $this->pdo->prepare(
                    'INSERT INTO rutina_personalizada (id_usuario, nombre, activa, id_dieta)
                     VALUES (?, ?, ?, ?)'
                );
                $stmt->execute([$userId, 'Rutina personalizada', $plan_personalizado, $id_dieta]);
            }

            $this->pdo->commit();
            return $userId;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Actualiza un usuario.
     */
    public function updateUser(int $id, string $nombre, string $cedula, string $password, int $plan_personalizado, int $id_dieta): void
    {
        $this->pdo->beginTransaction();

        try {
            if ($password !== '') {
                $stmt = $this->pdo->prepare('UPDATE usuarios SET nombre = ?, cedula = ?, contrasena = ?, plan_personalizado = ? WHERE id_usuario = ?');
                $stmt->execute([$nombre, $cedula, $password, $plan_personalizado, $id]);
            } else {
                $stmt = $this->pdo->prepare('UPDATE usuarios SET nombre = ?, cedula = ?, plan_personalizado = ? WHERE id_usuario = ?');
                $stmt->execute([$nombre, $cedula, $plan_personalizado, $id]);
            }

            // Simple update for routine and diet just like add
            $stmt = $this->pdo->prepare('DELETE FROM rutina_personalizada WHERE id_usuario = ?');
            $stmt->execute([$id]);

            if ($plan_personalizado || $id_dieta) {
                // Determine real id_dieta if they send 1
                $real_id_dieta = $id_dieta > 0 ? 1 : null;
                $stmt = $this->pdo->prepare(
                    'INSERT INTO rutina_personalizada (id_usuario, nombre, activa, id_dieta)
                     VALUES (?, ?, ?, ?)'
                );
                $stmt->execute([$id, 'Rutina personalizada', $plan_personalizado, $real_id_dieta]);
            }

            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Elimina un usuario por su ID.
     *
     * @param int $id El ID del usuario.
     * @return bool True si se eliminó correctamente.
     */
    public function deleteUser(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM usuarios WHERE id_usuario = ?');
        return $stmt->execute([$id]);
    }

    /**
     * Obtiene el total de un conteo usando una consulta scalar.
     *
     * @param string $sql Consulta SQL que retorna un solo valor.
     * @return int El resultado numérico de la consulta.
     */
    private function scalarCount(string $sql): int
    {
        return (int) $this->pdo->query($sql)->fetchColumn();
    }
}
